<?php

namespace App\Factory;

use App\Security\ApiToken;

class ApiTokenFactory
{

    /**
     * @param mixed $data
     * @return ApiToken
     * @throws \Exception
     */
    public function create(mixed $data): ApiToken
    {
        $apiToken = new ApiToken();

        if ($data !== null) {
            $apiToken->setUsername($data->username);
            $apiToken->setEnabled($data->enabled);
            $apiToken->setToken($this->generateToken());
        }

        return $apiToken;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
