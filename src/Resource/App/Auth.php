<?php

declare(strict_types=1);

namespace BearinUser\Resource\App;

use BEAR\Resource\ResourceObject;
use BearinUser\Service\AuthService;
use Ray\ValidateModule\Annotation\Valid;
use Ray\ValidateModule\Annotation\OnValidate;

class Auth extends ResourceObject
{
    public function __construct(
        private AuthService $authService
    ) {}

    #[Valid]
    public function onPost(string $email, string $password): static
    {
        $result = $this->authService->login($email, $password);
        
        if ($result['success']) {
            $this->body = [
                'message' => 'Login successful',
                'user' => $result['user']
            ];
        } else {
            $this->code = 401;
            $this->body = ['error' => 'Invalid credentials'];
        }

        return $this;
    }

    public function onDelete(): static
    {
        $this->authService->logout();
        $this->body = ['message' => 'Logout successful'];

        return $this;
    }

    #[OnValidate]
    public function onValidate(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }
}
