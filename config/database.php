<?php
/**
 * Configuración de la Base de Datos
 */

return [
    'host' => 'localhost',
    'dbname' => 'modern_blog',
    'username' => 'root',
    'password' => 'root', // Cambiar si tu instalación local tiene contraseña
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
