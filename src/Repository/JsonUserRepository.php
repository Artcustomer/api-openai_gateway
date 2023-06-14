<?php

namespace App\Repository;

use App\Factory\UserFactory;
use App\Security\User;
use Symfony\Component\Security\Core\User\UserInterface;

class JsonUserRepository implements IUserRepository
{

    private const FILE = '../data/users.json';

    private UserFactory $userFactory;

    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    /**
     * @param $identifier
     * @return User|null
     */
    public function findOneByIdentifier($identifier)
    {
        $userData = $this->findUser($identifier);
        $user = null;

        if ($userData !== null) {
            $user = $this->userFactory->create($userData);
        }

        return $user;
    }

    /**
     * @return UserInterface
     */
    public function createDefaultUser(): UserInterface
    {
        return $this->userFactory->create(null);
    }

    /**
     * @param string $username
     * @return false|mixed|string|null
     */
    private function findUser(string $username)
    {
        $fileContent = $this->loadFileContent();
        $result = null;

        if ($fileContent !== null) {
            $results = array_filter($fileContent, function ($item) use ($username) {
                return ($item->username === $username);
            });

            if (!empty($results)) {
                $result = reset($results);
            }
        }

        return $result;
    }

    /**
     * @return false|mixed|string|null
     */
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
}

