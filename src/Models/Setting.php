<?php

namespace App\Models;

use App\Database;
use PDO;

class Setting {
    public string $key;
    public ?string $value;
    public string $created_at;
    public string $updated_at;

    /**
     * Obtiene el valor de una configuración por su clave.
     */
    public static function get(string $key, ?string $default = null): ?string {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT value FROM settings WHERE `key` = :key");
            $stmt->execute(['key' => $key]);
            $value = $stmt->fetchColumn();
            return ($value !== false) ? $value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Guarda o actualiza una configuración.
     */
    public static function set(string $key, ?string $value): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO settings (`key`, `value`) 
            VALUES (:key, :value) 
            ON DUPLICATE KEY UPDATE `value` = :value_update
        ");
        return $stmt->execute([
            'key' => $key,
            'value' => $value,
            'value_update' => $value
        ]);
    }

    /**
     * Obtiene todas las configuraciones mapeadas por clave.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM settings");
        
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
}
