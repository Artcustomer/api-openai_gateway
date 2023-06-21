<?php

namespace App\Factory;

use App\Security\IApiUser;
use App\Security\User;

class UserFactory
{

    /**
     * @param mixed $data
     * @return User
     */
    public function create(mixed $data): User
    {
        $user = new User();
        $user->setPassword('');

        if ($data !== null) {
            $user->setId($data->id);
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
