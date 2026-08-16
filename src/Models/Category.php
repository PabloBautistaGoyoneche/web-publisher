<?php

namespace App\Models;

use App\Database;
use PDO;

class Category {
    public int $id;
    public string $name;
    public string $slug;
    public ?string $description;
    public string $created_at;

    /**
     * Obtiene todas las categorías.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = self::map($row);
        }
        return $categories;
    }

    /**
     * Busca una categoría por ID.
     */
    public static function find(int $id): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Busca una categoría por Slug.
     */
    public static function findBySlug(string $slug): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Obtiene el total de posts publicados en esta categoría.
     */
    public function getPostCount(): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE category_id = :category_id AND status = 'published'");
        $stmt->execute(['category_id' => $this->id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Mapea un registro a un objeto Category.
     */
    private static function map(array $row): self {
        $category = new self();
        $category->id = (int)$row['id'];
        $category->name = $row['name'];
        $category->slug = $row['slug'];
        $category->description = $row['description'];
        $category->created_at = $row['created_at'];
        return $category;
    }

    /**
     * Obtiene la cantidad total de categorías.
     */
    public static function count(): int {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    }

    /**
     * Crea una nueva categoría.
     */
    public static function create(string $name, string $slug, ?string $description): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)");
        return $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description
        ]);
    }

    /**
     * Actualiza una categoría existente.
     */
    public static function update(int $id, string $name, string $slug, ?string $description): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description
        ]);
    }

    /**
     * Elimina una categoría.
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
