<?php
/**
 * Empaquetador de Distribución de la Aplicación
 * Compila un archivo zip limpio excluyendo dependencias de desarrollo y credenciales locales.
 */

$zipName = 'web-publisher.zip';

// Eliminar zip anterior si existe
if (file_exists($zipName)) {
    unlink($zipName);
}

if (!class_exists('ZipArchive')) {
    echo "--------------------------------------------------\n";
    echo "AVISO: La extensión 'zip' de PHP no está activa en tu CLI local.\n";
    echo "Intentando usar la herramienta del sistema (tar) como respaldo...\n";
    echo "--------------------------------------------------\n";

    // Comando tar nativo en Windows 10/11 y Linux/macOS
    $cmd = 'tar -a -c -f ' . $zipName . ' ' .
           '--exclude=".git" ' .
           '--exclude="node_modules" ' .
           '--exclude="config/database.php" ' .
           '--exclude="database.sql" ' .
           '--exclude="package.php" ' .
           '--exclude="' . $zipName . '" ' .
           '--exclude=".gitignore" ' .
           '--exclude="package-lock.json" ' .
           '--exclude=".agents" ' .
           '--exclude=".gemini" ' .
           '--exclude="input.css" ' .
           '--exclude="tailwind.config.js" *';

    exec($cmd, $output, $returnCode);

    if ($returnCode === 0 && file_exists($zipName)) {
        echo "--------------------------------------------------\n";
        echo "¡COMPILACIÓN DE RESPALDO COMPLETADA CON ÉXITO!\n";
        echo "--------------------------------------------------\n";
        echo "Archivo generado con tar: $zipName\n";
        echo "Listo para descomprimir y ejecutar el instalador.\n";
        exit(0);
    } else {
        echo "Error: No se pudo crear el archivo zip utilizando 'tar'.\n";
        echo "Por favor, habilita la extensión 'zip' en tu archivo php.ini:\n";
        echo "1. Busca ';extension=zip' y cámbialo a 'extension=zip'.\n";
        exit(1);
    }
}

$zip = new ZipArchive();
if ($zip->open($zipName, ZipArchive::CREATE) !== true) {
    die("Error: No se pudo crear el archivo zip: $zipName\n");
}

$sourceDir = __DIR__;

// Patrones a excluir (archivos y carpetas)
$excludePatterns = [
    '/^\.git/',
    '/^node_modules/',
    '/^config\/database\.php$/', // Excluir credenciales locales
    '/^database\.sql$/',         // Excluir semilla SQL antigua
    '/^package\.php$/',          // Excluir el propio script
    '/^' . preg_quote($zipName) . '$/',
    '/^\.gitignore$/',
    '/^package-lock\.json$/',
    '/^\.agents/',
    '/^\.gemini/',
    '/^input\.css$/',
    '/^tailwind\.config\.js$/'
];

function shouldExclude($relativePath, $excludePatterns) {
    // Normalizar a formato slash Unix
    $relativePath = str_replace('\\', '/', $relativePath);
    
    // Evitar añadir carpetas vacías de exclusiones generales
    if (empty($relativePath)) {
        return true;
    }

    foreach ($excludePatterns as $pattern) {
        if (preg_match($pattern, $relativePath)) {
            return true;
        }
    }

    // Reglas para subcarpetas completas
    if (str_starts_with($relativePath, 'node_modules/') || 
        str_starts_with($relativePath, '.git/') || 
        str_starts_with($relativePath, '.agents/') || 
        str_starts_with($relativePath, '.gemini/')) {
        return true;
    }

    return false;
}

$filesCount = 0;
$directoryCount = 0;

$directoryIterator = new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);

foreach ($iterator as $file) {
    $filePath = $file->getRealPath();
    $relativePath = substr($filePath, strlen($sourceDir) + 1);

    if (shouldExclude($relativePath, $excludePatterns)) {
        continue;
    }

    if ($file->isDir()) {
        $zip->addEmptyDir($relativePath);
        $directoryCount++;
    } else {
        $zip->addFile($filePath, $relativePath);
        $filesCount++;
    }
}

$zip->close();

echo "--------------------------------------------------\n";
echo "¡COMPILACIÓN COMPLETADA CON ÉXITO!\n";
echo "--------------------------------------------------\n";
echo "Archivo generado:      $zipName\n";
echo "Archivos empaquetados: $filesCount\n";
echo "Directorios creados:   $directoryCount\n";
echo "--------------------------------------------------\n";
echo "Listo para descomprimir y ejecutar el instalador.\n";
