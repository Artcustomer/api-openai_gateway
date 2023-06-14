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
        $fileContent = $this->loadFileContent();
        $result = null;

        if ($fileContent !== null) {
            // TODO : validate data

            $numUsers = count($fileContent);
            $lastUser = $fileContent[$numUsers - 1];
            $lastUserId = $lastUser->id;

            $data->id = $lastUserId + 1;
            $data->enabled = true;

            $result = $this->userFactory->create($data);

            $hashedPassword = $this->passwordHasher->hashPassword($result, $data->password);

            $result->setPassword($hashedPassword);
            $data->password = $hashedPassword;

            $fileContent[] = $data;

            $this->writeFileContent($fileContent);
        }

        return $result;
    }

    public function removeUser(string $id): bool
    {
        $user = $this->getUser($id);
        $status = false;

        if ($user !== null) {

        }

        return $status;
    }

    public function getUser(string $id): ?UserInterface
    {
        $fileContent = $this->loadFileContent();
        $result = null;

        if ($fileContent !== null) {
            foreach ($fileContent as $index => $item) {
                if ($item->id === (int)$id) {
                    $result = $this->userFactory->create($item);
                    break;
                }
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

    public function hashPassword(string $value): string
    {
        return '';
    }
}