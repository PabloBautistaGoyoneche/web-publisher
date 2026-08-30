<?php
/**
 * Script temporal para ejecutar migraciones pendientes en producción
 */

$configPath = dirname(__DIR__) . '/config/database.php';
if (!file_exists($configPath)) {
    die("Error: No se encontró el archivo de configuración en config/database.php. Por favor, realiza la instalación primero.");
}

$dbConfig = require $configPath;

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
    
    // Crear tabla de control si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL UNIQUE,
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $migrationsDir = dirname(__DIR__) . '/src/Migrations';
    if (!is_dir($migrationsDir)) {
        die("Error: No se encontró la carpeta de migraciones en src/Migrations/.");
    }
    
    $files = glob($migrationsDir . '/*.sql');
    sort($files);
    
    echo "<h1>Ejecutando Migraciones Pendientes</h1>";
    echo "<ul>";
    
    $count = 0;
    foreach ($files as $file) {
        $name = basename($file);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = :name");
        $stmt->execute(['name' => $name]);
        $alreadyRun = (int)$stmt->fetchColumn();
        $stmt->closeCursor();
        
        if ($alreadyRun === 0) {
            $sql = file_get_contents($file);
            if (!empty(trim($sql))) {
                $pdo->exec($sql);
            }
            $stmtInsert = $pdo->prepare("INSERT INTO migrations (migration) VALUES (:name)");
            $stmtInsert->execute(['name' => $name]);
            $stmtInsert->closeCursor();
            echo "<li style='color: green;'><strong>✓ $name</strong> ejecutada con éxito.</li>";
            $count++;
        } else {
            echo "<li style='color: gray;'>$name (Ya ejecutada)</li>";
        }
    }
    
    echo "</ul>";
    echo "<p><strong>Se ejecutaron $count migraciones nuevas.</strong></p>";
    echo "<p style='color: red;'><strong>IMPORTANTE: Por seguridad, elimina este archivo (update_db.php) de tu servidor de producción inmediatamente después de ejecutarlo.</strong></p>";
    
} catch (Exception $e) {
    die("Error al ejecutar las migraciones: " . $e->getMessage());
}
