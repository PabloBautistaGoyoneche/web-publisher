<?php

namespace App;

use PDO;
use Exception;

class Database {
    private static ?PDO $instance = null;

    /**
     * Obtiene la instancia única de la conexión PDO.
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $configPath = dirname(__DIR__) . '/config/database.php';
            if (!file_exists($configPath)) {
                throw new Exception("El archivo de configuración de base de datos no existe.");
            }

            $config = require $configPath;

            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (Exception $e) {
                throw new Exception("Error al conectar con la base de datos: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * Fuerza el cierre y reinicio de la conexión PDO.
     */
    public static function resetConnection(): void {
        self::$instance = null;
    }
}
