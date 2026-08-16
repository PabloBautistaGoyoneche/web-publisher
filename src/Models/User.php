<?php

namespace App\Models;

use App\Database;
use PDO;

class User {
    public int $id;
    public string $username;
    public string $email;
    public string $role;
    public string $display_name;
    public ?string $bio;
    public ?string $avatar;
    public string $created_at;

    /**
     * Busca un usuario por ID.
     */
    public static function find(int $id): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Busca un usuario por Username.
     */
    public static function findByUsername(string $username): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        if (!$row) return null;
        return self::map($row);
    }

    /**
     * Verifica las credenciales de un usuario.
     */
    public static function verify(string $username, string $password): ?self {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password'])) {
            return self::map($row);
        }
        return null;
    }

    /**
     * Mapea un registro de la base de datos a un objeto User.
     */
    private static function map(array $row): self {
        $user = new self();
        $user->id = (int)$row['id'];
        $user->username = $row['username'];
        $user->email = $row['email'];
        $user->role = $row['role'];
        $user->display_name = $row['display_name'];
        $user->bio = $row['bio'];
        $user->avatar = $row['avatar'];
        $user->created_at = $row['created_at'];
        return $user;
    }
}
