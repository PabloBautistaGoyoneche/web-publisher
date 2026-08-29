<?php

namespace App\Models;

use App\Database;
use PDO;

class Cta {
    public int $id;
    public string $title;
    public string $description;
    public string $button_text;
    public string $link;
    public int $delay;
    public int $is_active;
    public string $created_at;
    public string $updated_at;

    /**
     * Obtiene todos los CTAs creados.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ctas ORDER BY created_at DESC");
        $ctas = [];
        while ($row = $stmt->fetch()) {
            $ctas[] = self::map($row);
        }
        return $ctas;
    }

    /**
     * Busca un CTA por su ID.
     */
    public static function find(int $id): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ctas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Obtiene el CTA activo (el que tiene is_active = 1).
     */
    public static function getActive(): ?self {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ctas WHERE is_active = 1 LIMIT 1");
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Crea un nuevo CTA en la base de datos.
     */
    public static function create(string $title, string $description, string $button_text, string $link, int $delay, int $is_active = 0): bool {
        $db = Database::getConnection();
        
        if ($is_active === 1) {
            self::deactivateAll();
        }

        $stmt = $db->prepare("
            INSERT INTO ctas (title, description, button_text, link, delay, is_active)
            VALUES (:title, :description, :button_text, :link, :delay, :is_active)
        ");
        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'button_text' => $button_text,
            'link' => $link,
            'delay' => $delay,
            'is_active' => $is_active
        ]);
    }

    /**
     * Actualiza un CTA existente en la base de datos.
     */
    public static function update(int $id, string $title, string $description, string $button_text, string $link, int $delay, int $is_active = 0): bool {
        $db = Database::getConnection();
        
        if ($is_active === 1) {
            self::deactivateAll();
        }

        $stmt = $db->prepare("
            UPDATE ctas
            SET title = :title, description = :description, button_text = :button_text, link = :link, delay = :delay, is_active = :is_active
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'button_text' => $button_text,
            'link' => $link,
            'delay' => $delay,
            'is_active' => $is_active
        ]);
    }

    /**
     * Elimina un CTA por su ID.
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM ctas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Activa un CTA específico y desactiva todos los demás.
     */
    public static function setActive(int $id): bool {
        self::deactivateAll();
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE ctas SET is_active = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Desactiva todos los CTAs.
     */
    private static function deactivateAll(): void {
        $db = Database::getConnection();
        $db->exec("UPDATE ctas SET is_active = 0");
    }

    /**
     * Mapea un registro de base de datos a un objeto Cta.
     */
    private static function map(array $row): self {
        $cta = new self();
        $cta->id = (int)$row['id'];
        $cta->title = $row['title'];
        $cta->description = $row['description'];
        $cta->button_text = $row['button_text'];
        $cta->link = $row['link'];
        $cta->delay = (int)$row['delay'];
        $cta->is_active = (int)$row['is_active'];
        $cta->created_at = $row['created_at'];
        $cta->updated_at = $row['updated_at'];
        return $cta;
    }
}
