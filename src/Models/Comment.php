<?php

namespace App\Models;

use App\Database;
use PDO;

class Comment {
    public int $id;
    public int $post_id;
    public string $author_name;
    public string $author_email;
    public string $content;
    public string $status;
    public string $created_at;

    /**
     * Obtiene todos los comentarios aprobados de un post ordenados por fecha ascendente.
     */
    public static function forPost(int $postId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM comments WHERE post_id = :post_id AND status = 'approved' ORDER BY created_at ASC");
        $stmt->execute(['post_id' => $postId]);

        $comments = [];
        while ($row = $stmt->fetch()) {
            $comments[] = self::map($row);
        }
        return $comments;
    }

    /**
     * Guarda un nuevo comentario en la base de datos.
     */
    public static function create(int $postId, string $authorName, string $authorEmail, string $content): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO comments (post_id, author_name, author_email, content, status) 
            VALUES (:post_id, :author_name, :author_email, :content, 'approved')
        ");
        return $stmt->execute([
            'post_id' => $postId,
            'author_name' => $authorName,
            'author_email' => $authorEmail,
            'content' => $content
        ]);
    }

    /**
     * Mapea un registro a un objeto Comment.
     */
    private static function map(array $row): self {
        $comment = new self();
        $comment->id = (int)$row['id'];
        $comment->post_id = (int)$row['post_id'];
        $comment->author_name = $row['author_name'];
        $comment->author_email = $row['author_email'];
        $comment->content = $row['content'];
        $comment->status = $row['status'];
        $comment->created_at = $row['created_at'];
        return $comment;
    }

    /**
     * Obtiene la cantidad total de comentarios.
     */
    public static function count(): int {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    }

    /**
     * Obtiene la cantidad de comentarios pendientes.
     */
    public static function countPending(): int {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
    }

    /**
     * Obtiene todos los comentarios en el sistema.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM comments ORDER BY created_at DESC");
        $comments = [];
        while ($row = $stmt->fetch()) {
            $comments[] = self::map($row);
        }
        return $comments;
    }

    /**
     * Aprueba un comentario.
     */
    public static function approve(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE comments SET status = 'approved' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Elimina un comentario.
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Obtiene el post al que pertenece este comentario.
     */
    public function getPost(): ?Post {
        return Post::find($this->post_id);
    }
}
