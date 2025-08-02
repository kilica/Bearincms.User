<?php

declare(strict_types=1);

namespace BearinUser\Service;

use Aura\Session\SessionFactory;
use Aura\Session\Segment;

class AuthService
{
    private Segment $segment;

    public function __construct(
        private UserService $userService,
        SessionFactory $sessionFactory
    ) {
        $session = $sessionFactory->newInstance($_COOKIE);
        $this->segment = $session->getSegment('BearinUser\Auth');
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userService->getUserByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false];
        }
        
        if ($user['status'] !== 'active') {
            return ['success' => false, 'error' => 'Account is not active'];
        }
        
        $this->segment->set('user_id', $user['id']);
        $this->segment->set('user_email', $user['email']);
        
        $this->userService->updateLastLogin($user['id']);
        
        unset($user['password']);
        
        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        $this->segment->clear();
    }

    public function getCurrentUser(): ?array
    {
        $userId = $this->segment->get('user_id');
        
        if (!$userId) {
            return null;
        }
        
        return $this->userService->getUserById($userId);
    }

    public function isLoggedIn(): bool
    {
        return $this->segment->get('user_id') !== null;
    }

    public function hasRole(string $role): bool
    {
        $user = $this->getCurrentUser();
        
        if (!$user) {
            return false;
        }
        
        return $user['role'] === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
