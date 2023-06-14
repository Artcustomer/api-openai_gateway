<?php

namespace App\Service;

use App\Factory\UserFactory;
use App\Trait\JsonUserTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class JsonUserService
{

    use JsonUserTrait;

    protected UserFactory $userFactory;
    protected UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserFactory $userFactory, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userFactory = $userFactory;
        $this->passwordHasher = $passwordHasher;
    }

    public function addUser($data): ?UserInterface
    {
        $fileContent = $this->loadFileContent('id');
        $user = null;

        if ($fileContent !== null) {
            // TODO : validate data

            $numUsers = count($fileContent);
            $lastUser = $fileContent[$numUsers - 1];
            $lastUserId = $lastUser->id;

            $data->id = $lastUserId + 1;
            $data->enabled = true;

            $user = $this->userFactory->create($data);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $data->password);

            $user->setPassword($hashedPassword);
            $data->password = $hashedPassword;

            $fileContent[] = $data;

            $this->writeFileContent($fileContent);
        }

        return $user;
    }

    public function removeUser(string $id): bool
    {
        $fileContent = $this->loadFileContent('id');
        $user = null;
        $status = false;

        if ($fileContent !== null) {
            $index = $this->searchByKey($fileContent, 'id', $id);

            if ($index !== null) {
                unset($fileContent[$index]);

                $this->writeFileContent($fileContent);

                return true;
            }
        }

        return $status;
    }

    public function getUser(int $id): ?UserInterface
    {
        $fileContent = $this->loadFileContent();
        $result = null;

        if ($fileContent !== null) {
            $index = $this->searchByKey($fileContent, 'id', $id);

            if ($index !== null) {
                $result = $this->userFactory->create($fileContent[$index]);
            }
        }

        return $result;
    }

    public function getUsers(): array
    {
        $fileContent = $this->loadFileContent();
        $result = [];

        if ($fileContent !== null) {
            $result = array_map(
                function ($item) {
                    return $this->userFactory->create($item);
                },
                $fileContent
            );
        }

        return $result;
    }

    private function searchByKey($array, $key, $value)
    {
        $index = array_search($value, array_column($array, $key));

        return $index !== false ? $index : null;
    }
}