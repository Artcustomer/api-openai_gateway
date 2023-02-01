<?php

namespace App\Library\Artcustomer\ApiUnit\Gateway;

use App\Library\Artcustomer\ApiUnit\Client\AbstractApiClient;
use App\Library\Artcustomer\ApiUnit\Event\IApiEventHandler;
use App\Library\Artcustomer\ApiUnit\Factory\ApiClientFactory;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;
use App\Library\Artcustomer\ApiUnit\Logger\IApiLogger;

abstract class AbstractApiGateway {

    private ApiClientFactory $clientFactory;

    /**
     * @var AbstractApiClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $clients = [];

    /**
	 * @var array
	 */
	protected $params = [];

    /**
     * AbstractApiGateway constructor.
     * @param string|NULL $clientClassName
     * @param array $clientArguments
     * @throws \ReflectionException
     */
    public function __construct(string $clientClassName = NULL, array $clientArguments = []) {
        $this->buildDependencies();

        if (NULL !== $clientClassName) {
            $this->addClient($clientClassName, $clientArguments, TRUE);
        }
    }

    private function buildDependencies() {
        $this->clientFactory = new ApiClientFactory();
    }

    /**
     * Initialize statement
     */
    abstract public function initialize(): void;

    /**
     * Implement a call to test API
     * @return IApiResponse
     */
    abstract public function test(): IApiResponse;

    public function addClient(string $className, array $arguments = [], bool $setAsDefault = true): object {
        if ($this->hasClient($className)) {
            throw new \Exception(sprintf('Client %s already exists !', $className));
        }

        $client = $this->clientFactory->create($className, $arguments);

        $this->clients[$className] = $client;

        if ($setAsDefault) {
            $this->setDefaultClient($className);
        }

        return $client;
    }

    public function getClient(string $className): ?object {
        $instance = null;

        if ($this->hasClient($className)) {
            $instance = $this->clients[$className];
        }

        return $instance;
    }

    public function removeClient(string $className): bool {
        $status = false;

        if ($this->hasClient($className)) {
            unset($this->clients[$className]);

            $status = true;
        }

        return $status;
    }

    public function hasClient(string $className): bool {
        return array_key_exists($className, $this->clients);
    }

    public function setDefaultClient(string $className) {
        $instance = $this->getClient($className);

        if (NULL !== $instance) {
            $this->client = $instance;
        }
    }

    /**
     * Set IApiLogger instance to one or multiple clients
     * @param IApiLogger $apiLogger
     */
    public function setApiLogger(IApiLogger $apiLogger, array $classNames): void {
        foreach ($classNames as $className) {
            $client = $this->getClient($className);

            if (NULL !== $client) {
                $client->setApiLogger($apiLogger);
            }
        }
    }

    /**
     * Set IApiEventHandler instance to one or multiple clients
     * @param IApiEventHandler $eventHandler
     */
    public function setEventHandler(IApiEventHandler $eventHandler, array $classNames): void {
        foreach ($classNames as $className) {
            $client = $this->getClient($className);

            if (NULL !== $client) {
                $client->setEventHandler($eventHandler);
            }
        }
    }
}
