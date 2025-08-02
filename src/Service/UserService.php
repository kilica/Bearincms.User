<?php

declare(strict_types=1);

namespace BearinUser\Service;

use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\AuraSqlInject;

class UserService
{
    use AuraSqlInject;

    public function getAllUsers(): array
    {
        $sql = "
            SELECT u.*, r.role, p.name, p.nickname, p.avatar_image, p.profile
            FROM users u
            LEFT JOIN roles r ON u.id = r.user_id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.deleted_at IS NULL
            ORDER BY u.created_at DESC
        ";
        
        return $this->pdo->fetchAll($sql);
    }

    public function getUserById(int $id): ?array
    {
        $sql = "
            SELECT u.*, r.role, p.name, p.nickname, p.avatar_image, p.profile
            FROM users u
            LEFT JOIN roles r ON u.id = r.user_id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.id = ? AND u.deleted_at IS NULL
        ";
        
        return $this->pdo->fetchOne($sql, [$id]) ?: null;
    }

    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ? AND deleted_at IS NULL";
        
        return $this->pdo->fetchOne($sql, [$email]) ?: null;
    }

    public function createUser(string $email, string $password, string $name = '', string $nickname = ''): int
    {
        $this->pdo->beginTransaction();
        
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $now = date('Y-m-d H:i:s');
            
            $sql = "
                INSERT INTO users (email, password, status, created_at, updated_at)
                VALUES (?, ?, 'active', ?, ?)
            ";
            $this->pdo->perform($sql, [$email, $hashedPassword, $now, $now]);
            $userId = (int) $this->pdo->lastInsertId();
            
            $sql = "INSERT INTO roles (user_id, role) VALUES (?, 'viewer')";
            $this->pdo->perform($sql, [$userId]);
            
            if ($name || $nickname) {
                $sql = "INSERT INTO profiles (user_id, name, nickname) VALUES (?, ?, ?)";
                $this->pdo->perform($sql, [$userId, $name, $nickname]);
            }
            
            $this->pdo->commit();
            
            return $userId;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function updateUser(int $id, string $email = null, string $status = null, string $role = null): void
    {
        $this->pdo->beginTransaction();
        
        try {
            $updates = [];
            $params = [];
            
            if ($email !== null) {
                $updates[] = 'email = ?';
                $params[] = $email;
            }
            
            if ($status !== null) {
                $updates[] = 'status = ?';
                $params[] = $status;
            }
            
            if (!empty($updates)) {
                $updates[] = 'updated_at = ?';
                $params[] = date('Y-m-d H:i:s');
                $params[] = $id;
                
                $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
                $this->pdo->perform($sql, $params);
            }
            
            if ($role !== null) {
                $sql = "UPDATE roles SET role = ? WHERE user_id = ?";
                $this->pdo->perform($sql, [$role, $id]);
            }
            
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function deleteUser(int $id): void
    {
        $sql = "UPDATE users SET deleted_at = ? WHERE id = ?";
        $this->pdo->perform($sql, [date('Y-m-d H:i:s'), $id]);
    }

    public function getUserProfile(int $userId): ?array
    {
        $sql = "SELECT * FROM profiles WHERE user_id = ?";
        
        return $this->pdo->fetchOne($sql, [$userId]) ?: null;
    }

    public function updateUserProfile(int $userId, string $name = null, string $nickname = null, string $avatarImage = null, string $profile = null): void
    {
        $existing = $this->getUserProfile($userId);
        
        if ($existing) {
            $updates = [];
            $params = [];
            
            if ($name !== null) {
                $updates[] = 'name = ?';
                $params[] = $name;
            }
            
            if ($nickname !== null) {
                $updates[] = 'nickname = ?';
                $params[] = $nickname;
            }
            
            if ($avatarImage !== null) {
                $updates[] = 'avatar_image = ?';
                $params[] = $avatarImage;
            }
            
            if ($profile !== null) {
                $updates[] = 'profile = ?';
                $params[] = $profile;
            }
            
            if (!empty($updates)) {
                $params[] = $userId;
                $sql = "UPDATE profiles SET " . implode(', ', $updates) . " WHERE user_id = ?";
                $this->pdo->perform($sql, $params);
            }
        } else {
            $sql = "INSERT INTO profiles (user_id, name, nickname, avatar_image, profile) VALUES (?, ?, ?, ?, ?)";
            $this->pdo->perform($sql, [$userId, $name, $nickname, $avatarImage, $profile]);
        }
    }

    public function updateLastLogin(int $userId): void
    {
        $sql = "UPDATE users SET last_logined_at = ? WHERE id = ?";
        $this->pdo->perform($sql, [date('Y-m-d H:i:s'), $userId]);
    }
}
