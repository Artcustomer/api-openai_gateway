<?php

namespace App\Service;

use App\Factory\UserFactory;
use App\Trait\JsonStorageTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JsonUserService implements IUserService
{

    use JsonStorageTrait;

    public const FIELD_ID = 'id';
    public const FIELD_USERNAME = 'username';
    public const FIELD_API_TOKEN = 'apiToken';

    protected UserFactory $userFactory;
    protected UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor
     *
     * @param UserFactory $userFactory
     * @param UserPasswordHasherInterface $passwordHasher
     * @param string $filePath
     */
    public function __construct(UserFactory $userFactory, UserPasswordHasherInterface $passwordHasher, string $filePath)
    {
        $this->userFactory = $userFactory;
        $this->passwordHasher = $passwordHasher;

        $this->setFilePath($filePath);
    }

    /**
     * Add user
     *
     * @param $data
     * @return UserInterface|null
     */
    public function addUser($data): ?UserInterface
    {
        $fileContent = $this->loadFileContent(self::FIELD_ID);
        $user = null;

        if ($fileContent !== null) {
            // TODO : validate data

            $index = $this->searchByKey($fileContent, self::FIELD_USERNAME, $data->username);

            if ($index === null) {
                $numUsers = count($fileContent);
                
                if ($numUsers > 0) {
                    $lastUser = $fileContent[$numUsers - 1];
                    $lastUserId = $lastUser->id;
                } else {
                    $lastUserId = 0;
                }

                $data->id = $lastUserId + 1;
                $data->enabled = true;

                $user = $this->userFactory->create($data);
                $hashedPassword = $this->passwordHasher->hashPassword($user, $data->password);

                $user->setPassword($hashedPassword);
                $data->password = $hashedPassword;

                $fileContent[] = $data;

                $this->writeFileContent($fileContent);
            }
        }

        return $user;
    }

    /**
     * Remove user
     *
     * @param string $id
     * @return bool
     */
    public function removeUser(string $id): bool
    {
        $fileContent = $this->loadFileContent(self::FIELD_ID);
        $status = false;

        if (!empty($fileContent)) {
            $index = $this->searchByKey($fileContent, self::FIELD_ID, $id);

            if ($index !== null) {
                unset($fileContent[$index]);

                $this->writeFileContent($fileContent);

                $status = true;
            }
        }

        return $status;
    }

    /**
     * Get user (id, username, apiToken)
     *
     * @param int|string $value
     * @param string $field
     * @return mixed
     */
    public function getUser(int|string $value, string $field = self::FIELD_ID): mixed
    {
        if (!in_array($field, [self::FIELD_ID, self::FIELD_USERNAME, self::FIELD_API_TOKEN])) {
            return null;
        }

        $fileContent = $this->loadFileContent();
        $result = null;

        if (!empty($fileContent)) {
            $index = $this->searchByKey($fileContent, $field, $value);

            if ($index !== null) {
                $result = $fileContent[$index];
            }
        }

        return $result;
    }

    /**
     * Get users
     *
     * @return array
     */
    public function getUsers(): array
    {
        $fileContent = $this->loadFileContent();
        $result = [];

        if (!empty($fileContent)) {
            $result = array_map(
                function ($item) {
                    return $this->userFactory->create($item);
                },
                $fileContent
            );
        }

        return $result;
    }

    /**
     * @return UserFactory
     */
    public function getFactory(): UserFactory
    {
        return $this->userFactory;
    }
}
