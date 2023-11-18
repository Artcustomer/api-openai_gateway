<?php

namespace App\Security;

use App\Entity\AbstractEntity;

/**
 * @author David
 */
class ApiToken extends AbstractEntity
{

    private string $username;
    private string $token;
    private bool $enabled = false;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function toObject(): \stdClass
    {
        $data = new \stdClass();
        $data->username = $this->username;
        $data->token = $this->token;
        $data->enabled = $this->enabled;

        return $data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'token' => $this->token,
            'enabled' => $this->enabled,
        ];
    }
}
