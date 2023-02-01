<?php

namespace App\Library\Artcustomer\ApiUnit\Factory;

use App\Library\Artcustomer\ApiUnit\Http\IApiRequest;

class ApiRequestFactory {

    /**
     * @var array
     */
    private $apiParams;

    /**
     * ApiRequestFactory constructor.
     * @param array $apiParams
     */
    public function __construct(array $apiParams) {
        $this->apiParams = $apiParams;
    }

    /**
     * Create AbstractApiRequest child instance
     * @param string $className
     * @param array $args
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param null $body
     * @param array $headers
     * @param bool $async
     * @param bool $secured
     * @param null $customData
     * @return IApiRequest|null
     */
    public function create(string $className, array $args, string $method, string $endpoint, array $query = [], $body = null, array $headers = [], $async = false, $secured = false, $customData = null): ?IApiRequest {
        if (empty($className)) {
            return null;
        }

        $args = $args ?? [];

        try {
            $reflection = new \ReflectionClass($className);
            $instance = $reflection->newInstanceArgs($args);
            $instance->setMethod(strtoupper($method));
            $instance->setEndpoint($endpoint);
            $instance->setQuery($query);
            $instance->setBody($body);
            $instance->setHeaders($headers);
            $instance->setAsync($async);
            $instance->setSecured($secured);
            $instance->setCustomData($customData);
            $instance->setup($this->apiParams);
            $instance->build();

            return $instance;
        } catch (\Exception $e) {
            
        }

        return null;
    }

    /**
     * Instantiate pre-configured AbstractApiRequest class
     * @param string $className
     * @param array $args
     * @return null|IApiRequest
     */
    public function instantiate(string $className, $args = []): ?IApiRequest {
        if (empty($className)) {
            return null;
        }

        try {
            $reflection = new \ReflectionClass($className);
            $instance = $reflection->newInstanceArgs($args);
            $instance->setup($this->apiParams);
            $instance->build();

            return $instance;
        } catch (\Exception $e) {
            
        }

        return null;
    }

}
