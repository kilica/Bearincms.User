<?php

declare(strict_types=1);

namespace BearinUser\Resource\App;

use BEAR\Resource\ResourceObject;
use BearinUser\Service\UserService;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\ValidateModule\Annotation\Valid;
use Ray\ValidateModule\Annotation\OnValidate;
use Ray\RoleModule\Annotation\RequiresRoles;

class User extends ResourceObject
{
    use AuraSqlInject;

    public function __construct(
        private UserService $userService
    ) {}

    #[RequiresRoles(['admin'])]
    public function onGet(int $id = null): static
    {
        if ($id === null) {
            $this->body = $this->userService->getAllUsers();
        } else {
            $this->body = $this->userService->getUserById($id);
        }

        return $this;
    }

    #[Valid]
    public function onPost(string $email, string $password, string $name = '', string $nickname = ''): static
    {
        $userId = $this->userService->createUser($email, $password, $name, $nickname);
        $this->code = 201;
        $this->body = ['id' => $userId, 'message' => 'User created successfully'];

        return $this;
    }

    #[RequiresRoles(['admin'])]
    #[Valid]
    public function onPut(int $id, string $email = null, string $status = null, string $role = null): static
    {
        $this->userService->updateUser($id, $email, $status, $role);
        $this->body = ['message' => 'User updated successfully'];

        return $this;
    }

    #[RequiresRoles(['admin'])]
    public function onDelete(int $id): static
    {
        $this->userService->deleteUser($id);
        $this->body = ['message' => 'User deleted successfully'];

        return $this;
    }

    #[OnValidate]
    public function onValidate(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'max:255',
            'nickname' => 'max:100'
        ];
    }
}
