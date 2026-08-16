<?php

namespace App\Models;

use App\Database;
use PDO;

class Page {
    public int $id;
    public string $title;
    public string $slug;
    public string $content;
    public string $created_at;
    public string $updated_at;

    /**
     * Obtiene todas las páginas estáticas.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM pages ORDER BY title ASC");
        
        $pages = [];
        while ($row = $stmt->fetch()) {
            $pages[] = self::map($row);
        }
        return $pages;
    }

    /**
     * Busca una página por ID.
     */
    public static function find(int $id): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM pages WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Busca una página por Slug.
     */
    public static function findBySlug(string $slug): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Crea una nueva página estática.
     */
    public static function create(string $title, string $slug, string $content): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO pages (title, slug, content) VALUES (:title, :slug, :content)");
        return $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'content' => $content
        ]);
    }

    /**
     * Actualiza una página estática existente.
     */
    public static function update(int $id, string $title, string $slug, string $content): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE pages SET title = :title, slug = :slug, content = :content WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'content' => $content
        ]);
    }

    /**
     * Elimina una página estática.
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Obtiene la cantidad total de páginas estáticas.
     */
    public static function count(): int {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM pages")->fetchColumn();
    }

    /**
     * Mapea un registro a un objeto Page.
     */
    private static function map(array $row): self {
        $page = new self();
        $page->id = (int)$row['id'];
        $page->title = $row['title'];
        $page->slug = $row['slug'];
        $page->content = $row['content'];
        $page->created_at = $row['created_at'];
        $page->updated_at = $row['updated_at'];
        return $page;
    }
}
