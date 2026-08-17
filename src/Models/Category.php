<?php

namespace App\Models;

use App\Database;
use PDO;

class Category {
    public int $id;
    public string $name;
    public string $slug;
    public ?string $description;
    public ?int $parent_id = null;
    public string $created_at;
    public int $sort_order = 0;

    /**
     * Obtiene todas las categorías.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = self::map($row);
        }
        return $categories;
    }

    /**
     * Obtiene solo las categorías principales (sin padre).
     */
    public static function parents(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY sort_order ASC, name ASC");
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = self::map($row);
        }
        return $categories;
    }

    /**
     * Obtiene las subcategorías asociadas a esta categoría.
     */
    public function getSubcategories(): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE parent_id = :parent_id ORDER BY sort_order ASC, name ASC");
        $stmt->execute(['parent_id' => $this->id]);
        $subcategories = [];
        while ($row = $stmt->fetch()) {
            $subcategories[] = self::map($row);
        }
        return $subcategories;
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
        $category->parent_id = isset($row['parent_id']) && $row['parent_id'] !== null ? (int)$row['parent_id'] : null;
        $category->created_at = $row['created_at'];
        $category->sort_order = (int)($row['sort_order'] ?? 0);
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
    public static function create(string $name, string $slug, ?string $description, ?int $parentId = null): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (name, slug, description, parent_id) VALUES (:name, :slug, :description, :parent_id)");
        return $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'parent_id' => $parentId
        ]);
    }

    /**
     * Actualiza una categoría existente.
     */
    public static function update(int $id, string $name, string $slug, ?string $description, ?int $parentId = null): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug, description = :description, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'parent_id' => $parentId
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

    /**
     * Actualiza el padre y el orden de ordenamiento de una categoría.
     */
    public static function updateParentAndOrder(int $id, ?int $parentId, int $sortOrder): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE categories SET parent_id = :parent_id, sort_order = :sort_order WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'parent_id' => $parentId,
            'sort_order' => $sortOrder
        ]);
    }
}
