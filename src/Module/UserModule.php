<?php

declare(strict_types=1);

namespace BearinUser\Module;

use BEAR\Sunday\Extension\Application\AbstractModule;
use BearinUser\Service\AuthService;
use BearinUser\Service\UserService;
use BearinUser\Service\PasswordService;
use BearinUser\Service\EmailService;

class UserModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(AuthService::class);
        $this->bind(UserService::class);
        $this->bind(PasswordService::class);
        $this->bind(EmailService::class);
    }
}
