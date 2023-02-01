<?php

namespace App\Library\Artcustomer\ApiUnit\Client;

use App\Library\Artcustomer\ApiUnit\Factory\ApiResponseFactory;
use App\Library\Artcustomer\ApiUnit\Factory\ApiRequestFactory;
use App\Library\Artcustomer\ApiUnit\Factory\ApiEventFactory;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;
use App\Library\Artcustomer\ApiUnit\Http\IApiRequest;
use App\Library\Artcustomer\ApiUnit\Logger\IApiLogger;
use App\Library\Artcustomer\ApiUnit\Event\IApiEventHandler;
use App\Library\Artcustomer\ApiUnit\Http\IHttpItem;
use App\Library\Artcustomer\ApiUnit\Mock\IAPIMock;
use App\Library\Artcustomer\ApiUnit\Normalizer\IResponseNormalizer;

abstract class AbstractApiClient {

    /**
     * @var array
     */
    protected $apiParams;

    /**
     * @var ApiResponseFactory
     */
    protected $responseFactory;

    /**
     * @var ApiRequestFactory
     */
    protected $requestFactory;

    /**
     * @var ApiEventFactory
     */
    protected $eventFactory;

    /**
     * @var IApiEventHandler
     */
    protected $eventHandler;

    /**
     * @var IApiLogger
     */
    protected $apiLogger;

    /**
     * @var string
     */
    protected $responseDecoratorClassName;

    /**
     * @var array
     */
    protected $responseDecoratorArguments = [];

    /**
     * @var string
     */
    protected $requestClassName;

    /**
     * @var array
     */
    protected $requestArguments = [];

    /**
     * @var bool
     */
    protected $enableEvents = FALSE;

    /**
     * @var bool
     */
    protected $enableListeners = FALSE;

    /**
     * @var bool
     */
    protected $enableMocks = TRUE;

    /**
     * @var bool
     */
    protected $isOperational = FALSE;

    /**
     * @var bool
     */
    protected $isEnabled = TRUE;

    /**
     * @var bool
     */
    protected $debugMode = FALSE;

    /**
     * @var array
     */
    private $normalizers = [];

    /**
     * @var array
     */
    private $validators = [];

    /**
     * @var array
     */
    private $mocks = [];

    /**
     * AbstractApiClient constructor.
     *
     * @param array $apiParams
     */
    public function __construct(array $apiParams = []) {
        $this->apiParams = $apiParams;
    }

    /**
     * Initialize client
     */
    abstract public function initialize(): void;

    /**
     * Setup Client
     */
    abstract protected function setupClient(): void;

    /**
     * Do sync request
     *
     * @param IApiRequest $request
     * @return IApiResponse
     */
    abstract protected function doRequest(IApiRequest $request): IApiResponse;

    /**
     * Do async request
     *
     * @param IApiRequest $request
     * @return IApiResponse
     */
    abstract protected function doRequestAsync(IApiRequest $request): IApiResponse;

    /**
     * Build and execute sync request
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param null $body
     * @param array $headers
     * @param bool $async
     * @param bool $secured
     * @param null $customData
     * @return IApiResponse
     */
    public function request(string $method, string $endpoint, array $query = [], $body = null, array $headers = [], $async = false, $secured = false, $customData = null): IApiResponse {
        if (!$this->isOperational) {
            return $this->responseFactory->create(500, 'Error while sending request', 'API is not operational, check parameters and initialization state.');
        }

        if (!$this->isEnabled) {
            return $this->responseFactory->create(500, 'Error while sending request', 'API is not enabled.');
        }

        $this->preBuildRequest($method, $endpoint, $query, $body, $headers);
        $request = $this->buildRequest($method, $endpoint, $query, $body, $headers, $async, $secured, $customData);

        if (NULL !== $request) {
            if ($async) {
                return $this->doRequestAsync($request);
            }

            return $this->doRequest($request);
        }

        return $this->responseFactory->create(500, 'Error while building request', sprintf('Unable to build %s request for endpoint "%s"', $method, $endpoint));
    }

    /**
     * Execute pre-built request
     *
     * @param IApiRequest $request
     * @return IApiResponse
     */
    public function executeRequest(?IApiRequest $request): IApiResponse {
        if (!$this->isOperational) {
            return $this->responseFactory->create(500, 'Error while building request', 'API is not operational, check parameters and initialization state.');
        }

        if (!$this->isEnabled) {
            return $this->responseFactory->create(500, 'Error while sending request', 'API is not enabled.');
        }

        if (NULL !== $request) {
            if ($request->isAsync()) {
                return $this->doRequestAsync($request);
            }

            return $this->doRequest($request);
        }

        return $this->responseFactory->create(500, 'Error while sending request', 'Request is not well formed');
    }

    /**
     * Register normalizer
     * @param string $className
     * @param array $params
     * @throws \ReflectionException
     */
    public function registerNormalizer(string $className, array $params = []): void {
        $reflection = new \ReflectionClass($className);
        $instance = $reflection->newInstanceArgs($params);
        $rule = $instance->getRule();

        if (empty($rule)) {
            throw new \Exception('Cannot register a Normalizer with empty property "rule"');
        }

        if (array_key_exists($rule, $this->normalizers)) {
            throw new \Exception(sprintf('A Normalizer is already registered with the rule "%s"', $rule));
        }

        $this->normalizers[$rule] = $instance;
    }

    /**
     * Unregister normalizer
     * @param string $rule
     * @return bool
     */
    public function unregisterNormalizer(string $rule): bool {
        if (!empty($rule)) {
            if (array_key_exists($rule, $this->normalizers)) {
                unset($this->normalizers[$rule]);

                return true;
            }
        }

        return false;
    }

    public function attachValidator(string $className) {
        $reflection = new \ReflectionClass($className);
        $instance = $reflection->newInstance();
        $rule = $instance->getRule();

        if (empty($rule)) {
            throw new \Exception('Cannot register a Validator with empty property "rule"');
        }

        if (array_key_exists($rule, $this->validators)) {
            throw new \Exception(sprintf('A Validator is already registered with the rule "%s"', $rule));
        }

        $this->validators[$rule] = $instance;
    }

    public function detachValidator(string $rule): bool {
        if (!empty($rule)) {
            if (array_key_exists($rule, $this->validators)) {
                unset($this->validators[$rule]);

                return true;
            }
        }

        return false;
    }

    /**
     * Add Mock
     * @param string $className
     * @param array $params
     * @throws \ReflectionException
     */
    public function addMock(string $className, array $params = []): void {
        $reflection = new \ReflectionClass($className);
        $instance = $reflection->newInstanceArgs($params);
        $name = $instance->getName();

        if (empty($name)) {
            throw new \Exception('Cannot add a Mock with empty property "name"');
        }

        if (array_key_exists($name, $this->mocks)) {
            throw new \Exception(sprintf('A Mock is already added with the name "%s"', $name));
        }

        $instance->build();

        $this->mocks[$name] = $instance;
    }

    /**
     * Remove mock
     * @param string $name
     * @return bool
     */
    public function removeMock(string $name): bool {
        if (!empty($name)) {
            if (array_key_exists($name, $this->mocks)) {
                unset($this->mocks[$name]);

                return true;
            }
        }

        return false;
    }

    /**
     * Trigger listener
     * @param string $listener
     * @param IHttpItem $$httpItem
     */
    protected function triggerListener(string $listener, IHttpItem $httpItem): void {
        if ($this->enableListeners) {
            if (method_exists($httpItem, $listener)) {
                call_user_func([$httpItem, $listener]);
            }
        }
    }

    /**
     * Trigger event
     * @param string $eventType
     * @param IApiRequest $request
     * @param IApiResponse|null $response
     */
    protected function triggerEvent(string $eventType, IApiRequest $request, IApiResponse $response = null): void {
        if ($this->enableEvents) {
            $this->onEvent($eventType, $request, $response);
        }
    }

    /**
     * Trigger external event
     * @param string $eventName
     * @param IApiRequest|null $request
     * @param IApiResponse|null $response
     */
    protected function triggerExternalEvent(string $eventName, IApiRequest $request = null, IApiResponse $response = null) {
        if ($this->enableEvents) {
            if (NULL !== $this->eventHandler) {
                $event = $this->eventFactory->create(ApiEventFactory::TYPE_EXTERNAL, $eventName, $request, $response);

                if (NULL !== $event) {
                    $this->eventHandler->handleEvent($event);
                }
            }
        }
    }

    /**
     * Event callback
     * @param string $eventType
     * @param IApiRequest $request
     * @param IApiResponse|null $response
     */
    protected function onEvent(string $eventType, IApiRequest $request, IApiResponse $response = null): void {
        // Override it only if you need it
        // Set 'enableEvents' to true to get the callback
    }

    /**
     * Callback before building request
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param $body
     * @param array $headers
     */
    protected function preBuildRequest(string $method, string $endpoint, array $query = [], $body = null, array $headers = []): void {
        // Override it only if you need it
    }

    /**
     * Initialize client
     * Call this method after params setup
     */
    protected function init() {
        $this->isOperational = TRUE;

        $this->buildDependencies();
        $this->setupClient();
    }

    /**
     * Build request
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param null $body
     * @param array $headers
     * @param bool $async
     * @param bool $secured
     * @param null $customData
     * @return null|IApiRequest
     */
    protected function buildRequest(string $method, string $endpoint, array $query = [], $body = null, array $headers = [], $async = false, $secured = false, $customData = null): ?IApiRequest {
        return $this->requestFactory->create($this->requestClassName, $this->requestArguments, $method, $endpoint, $query, $body, $headers, $async, $secured, $customData);
    }

    /**
     * Apply normalizer
     * @param IApiRequest $request
     * @param IApiResponse $response
     * @return IApiResponse
     */
    protected function applyNormalizer(IApiRequest $request, IApiResponse &$response): IApiResponse {
        /** @var IResponseNormalizer $normalizer */
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->match($request->getEndpoint())) {
                return $normalizer->normalize($response);
            }
        }

        return $response;
    }

    /**
     * Get available mock for request
     * @param IApiRequest $request
     * @return IAPIMock
     */
    protected function getAvailableMock(IApiRequest $request): ?IApiMock {
        /** @var IApiMock $mock */
        foreach ($this->mocks as $mock) {
            if ($mock->match($request->getEndpoint())) {
                return $mock;
            }
        }

        return NULL;
    }

    /**
     * Build client dependencies
     * @throws \ReflectionException
     */
    private function buildDependencies() {
        $decorator = NULL;

        if (NULL !== $this->responseDecoratorClassName) {
            $reflection = new \ReflectionClass($this->responseDecoratorClassName);
            $decorator = $reflection->newInstanceArgs($this->responseDecoratorArguments);
        }

        $this->responseFactory = new ApiResponseFactory($decorator);
        $this->requestFactory = new ApiRequestFactory($this->apiParams);
        $this->eventFactory = new ApiEventFactory();
    }

    /**
     * @param IApiLogger $apiLogger
     */
    public function setApiLogger(IApiLogger $apiLogger): void {
        $this->apiLogger = $apiLogger;
    }

    /**
     * @param IApiEventHandler $eventHandler
     */
    public function setEventHandler(IApiEventHandler $eventHandler): void {
        $this->eventHandler = $eventHandler;
    }

    /**
     * @return ApiResponseFactory
     */
    public function getResponseFactory(): ApiResponseFactory {
        return $this->responseFactory;
    }

    /**
     * @return ApiRequestFactory
     */
    public function getRequestFactory(): ApiRequestFactory {
        return $this->requestFactory;
    }

}
