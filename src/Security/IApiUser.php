<?php

namespace App\Security;

interface IApiUser
{

    /**
     * @param string $apiToken
     * @return self
     */
    public function setApiToken(string $apiToken): self;

    /**
     * @return string
     */
    public function getApiToken(): string;
}
