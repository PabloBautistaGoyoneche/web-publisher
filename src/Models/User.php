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
     * Actualiza el display name de un usuario.
     */
    public static function updateDisplayName(int $id, string $displayName): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET display_name = :display_name WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'display_name' => $displayName
        ]);
    }

    /**
     * Actualiza el perfil de un usuario.
     */
    public static function updateProfile(int $id, string $username, string $email, string $displayName, ?string $bio, ?string $avatar, ?string $newPassword = null): bool {
        $db = Database::getConnection();
        
        $sql = "UPDATE users SET username = :username, email = :email, display_name = :display_name, bio = :bio, avatar = :avatar";
        $params = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'display_name' => $displayName,
            'bio' => $bio,
            'avatar' => $avatar
        ];
        
        if (!empty($newPassword)) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        }
        
        $sql .= " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
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
