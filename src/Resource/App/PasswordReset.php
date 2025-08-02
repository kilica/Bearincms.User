<?php

declare(strict_types=1);

namespace BearinUser\Resource\App;

use BEAR\Resource\ResourceObject;
use BearinUser\Service\PasswordService;
use Ray\ValidateModule\Annotation\Valid;
use Ray\ValidateModule\Annotation\OnValidate;

class PasswordReset extends ResourceObject
{
    public function __construct(
        private PasswordService $passwordService
    ) {}

    #[Valid]
    public function onPost(string $email): static
    {
        $result = $this->passwordService->sendPasswordResetEmail($email);
        
        if ($result) {
            $this->body = ['message' => 'Password reset email sent'];
        } else {
            $this->code = 404;
            $this->body = ['error' => 'Email not found'];
        }

        return $this;
    }

    #[Valid]
    public function onPut(string $token, string $password): static
    {
        $result = $this->passwordService->resetPassword($token, $password);
        
        if ($result) {
            $this->body = ['message' => 'Password reset successful'];
        } else {
            $this->code = 400;
            $this->body = ['error' => 'Invalid or expired token'];
        }

        return $this;
    }

    #[OnValidate]
    public function onValidate(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8'
        ];
    }
}
