<?php

namespace App\Service;

/**
 * @author David
 */
class EnvService
{

    /**
     * Get var
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
