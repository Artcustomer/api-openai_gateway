<?php

namespace App\Service;

use App\Factory\UserFactory;
use Symfony\Component\Security\Core\User\UserInterface;

interface IUserService
{

    /**
     * @param $data
     * @return UserInterface|null
     */
    public function addUser($data): ?UserInterface;

    /**
     * @param string $id
     * @return bool
     */
    public function removeUser(string $id): bool;

    /**
     * @param int|string $value
     * @param string $field
     * @return mixed
     */
    public function getUser(int|string $value, string $field = 'id'): mixed;

    /**
     * @return array
     */
    public function getUsers(): array;

    /**
     * @return UserFactory
     */
    public function getFactory(): UserFactory;
}
