<?php

namespace App\Repository;

use App\Service\JsonApiTokenService;

class JsonAccessTokenRepository implements IUserRepository
{

    private JsonApiTokenService $apiTokenService;

    /**
     * Constructor
     *
     * @param JsonApiTokenService $apiTokenService
     */
    public function __construct(JsonApiTokenService $apiTokenService)
    {
        $this->apiTokenService = $apiTokenService;
    }

    /**
     * @param $identifier
     * @return mixed
     * @throws \Exception
     */
    public function findOneByIdentifier($identifier): mixed
    {
        $apiTokenData = $this->apiTokenService->getApiToken($identifier, JsonApiTokenService::FIELD_TOKEN);
        $apiToken = null;

        if ($apiTokenData !== null) {
            $apiToken = $this->apiTokenService->getFactory()->create($apiTokenData);
        }

        return $apiToken;
    }
}

