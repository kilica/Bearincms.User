<?php

declare(strict_types=1);

namespace BearinUser\Tests\Service;

use BearinUser\Service\AuthService;
use BearinUser\Service\UserService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private UserService|MockObject $userService;

    protected function setUp(): void
    {
        $this->userService = $this->createMock(UserService::class);
    }

    public function testLoginSuccess(): void
    {
        $this->userService
            ->method('getUserByEmail')
            ->willReturn([
                'id' => 1,
                'email' => 'test@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'status' => 'active'
            ]);

        $this->userService
            ->expects($this->once())
            ->method('updateLastLogin');

        $result = $this->authService->login('test@example.com', 'password');
        
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user', $result);
    }

    public function testLoginFailure(): void
    {
        $this->userService
            ->method('getUserByEmail')
            ->willReturn(null);

        $result = $this->authService->login('test@example.com', 'wrongpassword');
        
        $this->assertFalse($result['success']);
    }
}
