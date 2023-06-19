<?php

namespace App\Trait;

trait ApiUserTrait
{

    private string $apiToken = '';

    /**
     * @param string $apiToken
     * @return ApiUserTrait
     */
    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }
}
