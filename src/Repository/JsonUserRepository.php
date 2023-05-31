<?php

namespace App\Repository;

use App\Security\User;
use Symfony\Component\Security\Core\User\UserInterface;

class JsonUserRepository implements IUserRepository
{

    private const FILE = '../data/users.json';

    public function findOneByIdentifier($identifier) {
        $userData = $this->findUser($identifier);
        $user = null;

        if ($userData !== null) {
            $user = $this->userFactory($userData);
        }

        return $user;
    }

    public function createDefaultUser(): UserInterface
    {
        return $this->userFactory(null);
    }

    private function findUser(string $username)
    {
        $fileContent = $this->loadFileContent();
        $result = null;

        if ($fileContent !== null) {
            $results = array_filter($fileContent, function($item) use ($username) {
                return ($item->username === $username);
            });

            if (!empty($results)) {
                $result = reset($results);
            }
        }

        return $result;
    }

    private function loadFileContent()
    {
        $content = null;

        try {
            $content = file_get_contents(self::FILE);
            $content = json_decode($content);
        } catch (\Exception $e) {

        }

        return $content;
    }

    private function userFactory($data)
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
        }

        return $user;
    }
}

