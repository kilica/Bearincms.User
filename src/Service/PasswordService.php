<?php

declare(strict_types=1);

namespace BearinUser\Service;

use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\AuraSqlInject;
use Ramsey\Uuid\Uuid;

class PasswordService
{
    use AuraSqlInject;

    public function __construct(
        private UserService $userService,
        private EmailService $emailService
    ) {}

    public function sendPasswordResetEmail(string $email): bool
    {
        $user = $this->userService->getUserByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        $token = Uuid::uuid4()->toString();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "
            INSERT INTO password_reset_tokens (user_id, token, expires_at, created_at)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            token = VALUES(token),
            expires_at = VALUES(expires_at),
            created_at = VALUES(created_at)
        ";
        
        $this->pdo->perform($sql, [$user['id'], $token, $expiresAt, date('Y-m-d H:i:s')]);
        
        return $this->emailService->sendPasswordResetEmail($email, $token);
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $sql = "
            SELECT user_id FROM password_reset_tokens
            WHERE token = ? AND expires_at > NOW() AND used_at IS NULL
        ";
        
        $tokenData = $this->pdo->fetchOne($sql, [$token]);
        
        if (!$tokenData) {
            return false;
        }
        
        $this->pdo->beginTransaction();
        
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users SET password = ?, updated_at = ? WHERE id = ?";
            $this->pdo->perform($sql, [$hashedPassword, date('Y-m-d H:i:s'), $tokenData['user_id']]);
            
            $sql = "UPDATE password_reset_tokens SET used_at = ? WHERE token = ?";
            $this->pdo->perform($sql, [date('Y-m-d H:i:s'), $token]);
            
            $this->pdo->commit();
            
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function cleanupExpiredTokens(): void
    {
        $sql = "DELETE FROM password_reset_tokens WHERE expires_at < NOW()";
        $this->pdo->perform($sql);
    }
}
