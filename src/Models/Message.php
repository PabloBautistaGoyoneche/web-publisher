<?php

namespace App\Models;

use App\Database;
use PDO;

class Message {
    public int $id;
    public string $name;
    public string $email;
    public string $subject;
    public string $message;
    public string $created_at;

    /**
     * Obtiene todos los mensajes de contacto.
     */
    public static function all(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM messages ORDER BY created_at DESC");
        
        $messages = [];
        while ($row = $stmt->fetch()) {
            $messages[] = self::map($row);
        }
        return $messages;
    }

    /**
     * Registra una nueva consulta de contacto.
     */
    public static function create(string $name, string $email, string $subject, string $message): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO messages (name, email, subject, message) 
            VALUES (:name, :email, :subject, :message)
        ");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ]);
    }

    /**
     * Elimina un mensaje de la base de datos.
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM messages WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Obtiene la cantidad total de mensajes recibidos.
     */
    public static function count(): int {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    }

    /**
     * Mapea un registro a un objeto Message.
     */
    private static function map(array $row): self {
        $msg = new self();
        $msg->id = (int)$row['id'];
        $msg->name = $row['name'];
        $msg->email = $row['email'];
        $msg->subject = $row['subject'];
        $msg->message = $row['message'];
        $msg->created_at = $row['created_at'];
        return $msg;
    }
}
