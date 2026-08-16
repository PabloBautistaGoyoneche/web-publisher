<?php

namespace App\Models;

use App\Database;
use PDO;

class Post {
    public int $id;
    public int $user_id;
    public int $category_id;
    public string $title;
    public string $slug;
    public ?string $excerpt;
    public string $content;
    public ?string $featured_image;
    public string $status;
    public int $views_count;
    public string $created_at;
    public string $updated_at;

    // Cache instances to avoid multiple queries
    private ?User $author = null;
    private ?Category $category = null;

    /**
     * Obtiene todos los posts publicados ordenados por fecha descendente.
     */
    public static function allPublished(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC");
        
        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = self::map($row);
        }
        return $posts;
    }

    /**
     * Obtiene los últimos N posts publicados.
     */
    public static function latest(int $limit = 5): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = self::map($row);
        }
        return $posts;
    }

    /**
     * Busca un post por ID.
     */
    public static function find(int $id): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Busca un post por Slug.
     */
    public static function findBySlug(string $slug): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE slug = :slug AND status = 'published'");
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Obtiene los posts publicados por Categoría.
     */
    public static function findByCategory(int $categoryId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE category_id = :category_id AND status = 'published' ORDER BY created_at DESC");
        $stmt->execute(['category_id' => $categoryId]);

        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = self::map($row);
        }
        return $posts;
    }

    public static function search(string $query): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * FROM posts 
            WHERE status = 'published' 
              AND (title LIKE :q1 OR content LIKE :q2 OR excerpt LIKE :q3) 
            ORDER BY created_at DESC
        ");
        $searchTerm = "%{$query}%";
        $stmt->execute([
            'q1' => $searchTerm,
            'q2' => $searchTerm,
            'q3' => $searchTerm
        ]);

        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = self::map($row);
        }
        return $posts;
    }

    /**
     * Incrementa el número de vistas de este post.
     */
    public function incrementViews(): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE posts SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $this->id]);
        $this->views_count++;
    }

    /**
     * Obtiene el autor del post.
     */
    public function getAuthor(): User {
        if ($this->author === null) {
            $this->author = User::find($this->user_id);
        }
        return $this->author;
    }

    /**
     * Obtiene la categoría del post.
     */
    public function getCategory(): Category {
        if ($this->category === null) {
            $this->category = Category::find($this->category_id);
        }
        return $this->category;
    }

    /**
     * Obtiene los comentarios aprobados de este post.
     */
    public function getComments(): array {
        return Comment::forPost($this->id);
    }

    /**
     * Obtiene posts relacionados (en la misma categoría, excluyendo el actual).
     */
    public function getRelated(int $limit = 3): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * FROM posts 
            WHERE category_id = :category_id 
              AND id != :current_id 
              AND status = 'published' 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->bindValue(':current_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = self::map($row);
        }
        return $posts;
    }

    /**
     * Mapea un registro a un objeto Post.
     */
    private static function map(array $row): self {
        $post = new self();
        $post->id = (int)$row['id'];
        $post->user_id = (int)$row['user_id'];
        $post->category_id = (int)$row['category_id'];
        $post->title = $row['title'];
        $post->slug = $row['slug'];
        $post->excerpt = $row['excerpt'];
        $post->content = $row['content'];
        $post->featured_image = $row['featured_image'];
        $post->status = $row['status'];
        $post->views_count = (int)$row['views_count'];
        $post->created_at = $row['created_at'];
        $post->updated_at = $row['updated_at'];
        return $post;
    }

    /**
     * Obtiene la cantidad total de entradas (publicadas y borradores).
     */
    public static function count(): int {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    }

    /**
     * Obtiene la suma total de visitas de todos los posts.
     */
    public static function totalViews(): int {
        $db = Database::getConnection();
        $views = $db->query("SELECT SUM(views_count) FROM posts")->fetchColumn();
        return $views ? (int)$views : 0;
    }

    /**
     * Obtiene todas las entradas (publicadas y borradores) ordenadas por fecha descendente.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM posts ORDER BY created_at DESC");
        
        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = self::map($row);
        }
        return $posts;
    }

    /**
     * Crea un nuevo post.
     */
    public static function create(int $userId, int $categoryId, string $title, string $slug, ?string $excerpt, string $content, ?string $featuredImage, string $status): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, featured_image, status) 
            VALUES (:user_id, :category_id, :title, :slug, :excerpt, :content, :featured_image, :status)
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => $featuredImage,
            'status' => $status
        ]);
    }

    /**
     * Actualiza un post existente.
     */
    public static function update(int $id, int $categoryId, string $title, string $slug, ?string $excerpt, string $content, ?string $featuredImage, string $status): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE posts 
            SET category_id = :category_id, 
                title = :title, 
                slug = :slug, 
                excerpt = :excerpt, 
                content = :content, 
                featured_image = :featured_image, 
                status = :status 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => $featuredImage,
            'status' => $status
        ]);
    }

    /**
     * Elimina un post.
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
