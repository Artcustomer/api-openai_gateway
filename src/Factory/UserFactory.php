<?php

namespace App\Factory;

use App\Security\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory
{

    public function create($data): UserInterface
    {
        $user = new User();
        $user->setPassword('');

        if ($data !== null) {
            $user->setUsername($data->username);
            $user->setPassword($data->password);
            $user->setFirstName($data->firstName);
            $user->setLastName($data->lastName);
            $user->setDescription($data->description);
            $user->setRoles($data->roles);
            $user->setEnabled($data->enabled);
        }

        return $user;
    }
}