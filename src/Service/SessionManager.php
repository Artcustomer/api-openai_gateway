<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author David
 */
class SessionManager
{

    private SessionInterface $session;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * Get from session
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->session->get($name, $default);
    }

    /**
     * Set in session
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, mixed $value): void
    {
        $this->session->set($name, $value);
    }

    /**
     * Get all values in session
     *
     * @return array
     */
    public function all(): array
    {
        return $this->session->all();
    }

    /**
     * Clear all values in session
     *
     * @return void
     */
    public function clear(): void
    {
        $this->session->clear();
    }
}
