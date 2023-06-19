<?php

namespace App\Repository;

use App\Service\IUserService;
use App\Service\JsonUserService;

class JsonAccessTokenRepository implements IUserRepository
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
        $userData = $this->userService->getUser($identifier, JsonUserService::FIELD_API_TOKEN);
        $user = null;

        if ($userData !== null) {
            $user = $this->userService->getFactory()->create($userData);
        }

        return $user;
    }
}

