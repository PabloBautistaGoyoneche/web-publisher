<?php
header('Content-Type: text/plain; charset=utf-8');

echo "=== DIAGNÓSTICO DE ESTRUCTURA DE ARCHIVOS ===\n\n";

$root = dirname(__DIR__);
echo "Directorio Raíz detectado: " . $root . "\n";
echo "Archivo index.php en public: " . (file_exists(__DIR__ . '/index.php') ? 'SÍ' : 'NO') . "\n";
echo "Archivo install.php en public: " . (file_exists(__DIR__ . '/install.php') ? 'SÍ' : 'NO') . "\n";

echo "\n--- Directorio src/ ---\n";
$srcPath = $root . '/src';
if (is_dir($srcPath)) {
    echo "Carpeta src/ existe.\n";
    echo "Archivo src/Database.php: " . (file_exists($srcPath . '/Database.php') ? 'SÍ' : 'NO') . "\n";
    echo "Archivo src/Helpers.php: " . (file_exists($srcPath . '/Helpers.php') ? 'SÍ' : 'NO') . "\n";
    
    echo "\n--- Directorio src/Models/ ---\n";
    $modelsPath = $srcPath . '/Models';
    if (is_dir($modelsPath)) {
        echo "Carpeta src/Models/ existe.\n";
        echo "Archivos en src/Models/:\n";
        $files = scandir($modelsPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "  - " . $file . " (" . filesize($modelsPath . '/' . $file) . " bytes)\n";
            }
        }
    } else {
        echo "Carpeta src/Models/ NO existe.\n";
    }
} else {
    echo "Carpeta src/ NO existe.\n";
}

echo "\n--- Búsqueda de carpetas duplicadas (src/src) ---\n";
if (is_dir($root . '/src/src')) {
    echo "¡ATENCIÓN! Carpeta duplicada 'src/src' DETECTADA.\n";
}
if (is_dir($root . '/src/public')) {
    echo "¡ATENCIÓN! Carpeta duplicada 'src/public' DETECTADA.\n";
}

echo "\n--- Búsqueda en la carpeta public/src (Extracción errónea) ---\n";
if (is_dir(__DIR__ . '/src')) {
    echo "¡ATENCIÓN! Existe una carpeta 'src' dentro de public/ ('public/src/'). Esto indica que el ZIP se extrajo en la carpeta incorrecta.\n";
}
