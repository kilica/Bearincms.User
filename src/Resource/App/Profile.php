<?php

declare(strict_types=1);

namespace BearinUser\Resource\App;

use BEAR\Resource\ResourceObject;
use BearinUser\Service\UserService;
use Ray\ValidateModule\Annotation\Valid;
use Ray\ValidateModule\Annotation\OnValidate;

class Profile extends ResourceObject
{
    public function __construct(
        private UserService $userService
    ) {}

    public function onGet(int $userId): static
    {
        $this->body = $this->userService->getUserProfile($userId);

        return $this;
    }

    #[Valid]
    public function onPut(int $userId, string $name = null, string $nickname = null, string $avatarImage = null, string $profile = null): static
    {
        $this->userService->updateUserProfile($userId, $name, $nickname, $avatarImage, $profile);
        $this->body = ['message' => 'Profile updated successfully'];

        return $this;
    }

    #[OnValidate]
    public function onValidate(): array
    {
        return [
            'name' => 'max:255',
            'nickname' => 'max:100',
            'avatarImage' => 'url',
            'profile' => 'max:1000'
        ];
    }
}
