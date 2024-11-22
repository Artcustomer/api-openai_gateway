<?php

namespace App\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractAPIClientService
{

    protected EventDispatcherInterface $eventDispatcher;
    protected SessionManager $sessionManager;
    protected bool $apiKeyInEnv = false;
    protected bool $apiKeyInSession = false;

    /**
     * @return void
     */
    abstract public function initialize(): void;

    /**
     * @return bool
     */
    abstract public function isApiKeyAvailable(): bool;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return void
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param SessionManager $sessionManager
     * @return void
     */
    public function setSessionManager(SessionManager $sessionManager): void
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * @return bool
     */
    public function isApiKeyInEnv(): bool
    {
        return $this->apiKeyInEnv;
    }

    /**
     * @return bool
     */
    public function isApiKeyInSession(): bool
    {
        return $this->apiKeyInSession;
    }
}