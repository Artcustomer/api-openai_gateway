<?php

namespace App\Repository;

use App\Service\IUserService;
use App\Service\JsonUserService;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David
 */
class JsonUserRepository implements IUserRepository
{

    private IUserService $userService;

    /**
     * Constructor
     *
     * @param JsonUserService $userService
     */
    public function __construct(JsonUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function findOneByIdentifier($identifier): mixed
    {
        $userData = $this->userService->getUser($identifier, JsonUserService::FIELD_USERNAME);
        $user = null;

        if ($userData !== null) {
            $user = $this->userService->getFactory()->create($userData);
        }

        return $user;
    }

    /**
     * @return UserInterface
     */
    public function createDefaultUser(): UserInterface
    {
        return $this->userService->getFactory()->create(null);
    }
}

