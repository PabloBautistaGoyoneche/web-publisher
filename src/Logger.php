<?php
namespace App;

/**
 * Clase Logger para registrar errores y excepciones en la base de datos
 */
class Logger {
    /**
     * Registra un mensaje de log.
     * Si la base de datos no está disponible, hace un fallback a error_log nativo.
     */
    public static function log(string $level, string $message, ?string $file = null, ?int $line = null, ?string $trace = null): void {
        try {
            // Solo registrar si el archivo de base de datos existe para evitar bucles durante instalación
            $configPath = dirname(__DIR__) . '/config/database.php';
            if (!file_exists($configPath)) {
                self::fallbackLog($level, $message, $file, $line);
                return;
            }

            $db = \App\Database::getConnection();
            
            // Asegurar que la tabla exista (en caso de que la migración no haya corrido todavía)
            $db->exec("CREATE TABLE IF NOT EXISTS system_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                level VARCHAR(20) NOT NULL,
                message TEXT NOT NULL,
                file VARCHAR(255) DEFAULT NULL,
                line INT DEFAULT NULL,
                trace TEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            $stmt = $db->prepare("INSERT INTO system_logs (level, message, file, line, trace) VALUES (:level, :message, :file, :line, :trace)");
            $stmt->execute([
                'level' => $level,
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'trace' => $trace
            ]);
        } catch (\Throwable $e) {
            self::fallbackLog($level, $message, $file, $line);
        }
    }

    /**
     * Log de respaldo en el archivo nativo de errores del servidor.
     */
    private static function fallbackLog(string $level, string $message, ?string $file, ?int $line): void {
        $formatted = sprintf(
            "[%s] [%s] %s %s %s",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            $file ? "in $file" : "",
            $line ? "on line $line" : ""
        );
        error_log($formatted);
    }
}
