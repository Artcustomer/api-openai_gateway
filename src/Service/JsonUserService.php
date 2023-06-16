<?php

namespace App\Service;

use App\Factory\UserFactory;
use App\Trait\JsonUserTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JsonUserService implements IUserService
{

    use JsonUserTrait;

    public const FIELD_ID = 'id';
    public const FIELD_USERNAME = 'username';

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
        $fileContent = $this->loadFileContent('id');
        $user = null;

        if (!empty($fileContent)) {
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

    /**
     * Remove user
     *
     * @param string $id
     * @return bool
     */
    public function removeUser(string $id): bool
    {
        $fileContent = $this->loadFileContent('id');
        $status = false;

        if (!empty($fileContent)) {
            $index = $this->searchByKey($fileContent, 'id', $id);

            if ($index !== null) {
                unset($fileContent[$index]);

                $this->writeFileContent($fileContent);

                $status = true;
            }
        }

        return $status;
    }

    /**
     * Get user (id or username)
     *
     * @param int|string $value
     * @param string $field
     * @return mixed
     */
    public function getUser(int|string $value, string $field = self::FIELD_ID): mixed
    {
        if (!in_array($field, [self::FIELD_ID, self::FIELD_USERNAME])) {
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

    /**
     * @param $array
     * @param $key
     * @param $value
     * @return int|string|null
     */
    private function searchByKey($array, $key, $value)
    {
        $index = array_search($value, array_column($array, $key));

        return $index !== false ? $index : null;
    }
}
