<?php

namespace App\Library\Artcustomer\ApiUnit\Factory;

use App\Library\Artcustomer\ApiUnit\Client\AbstractApiClient;

class ApiClientFactory {

    /**
     * ApiClientFactory constructor.
     */
    public function __construct() {
        
    }

    /**
     * Create Client
     * @param string $clientClassName
     * @param array $clientArguments
     * @throws \ReflectionException
     */
    public function create(string $className, array $arguments = []): ?AbstractApiClient {
        $reflection = new \ReflectionClass($className);
        $instance = $reflection->newInstanceArgs($arguments);

        return $instance;
    }

}
