<?php

declare(strict_types=1);

namespace BearinUser\Service;

class EmailService
{
    public function sendPasswordResetEmail(string $email, string $token): bool
    {
        $resetUrl = "http://localhost/password-reset?token=" . $token;
        
        $subject = "Password Reset Request";
        $message = "
            Hello,
            
            You have requested a password reset. Please click the link below to reset your password:
            
            {$resetUrl}
            
            This link will expire in 1 hour.
            
            If you did not request this password reset, please ignore this email.
            
            Best regards,
            Bearin CMS Team
        ";
        
        $headers = [
            'From: noreply@bearin.cms',
            'Reply-To: noreply@bearin.cms',
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        return mail($email, $subject, $message, implode("\r\n", $headers));
    }
}
