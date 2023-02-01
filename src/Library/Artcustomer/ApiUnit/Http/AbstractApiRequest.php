<?php

namespace App\Library\Artcustomer\ApiUnit\Http;

abstract class AbstractApiRequest implements IApiRequest {

    /**
     * @var string
     */
    protected $uri = null;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $query;

    /**
     * @var
     */
    protected $body;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var bool
     */
    protected $async = false;

    /**
     * @var bool
     */
    protected $secured = false;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $headersParams = [];

    /**
     * @var null
     */
    protected $customData = null;

    /**
     * AbstractApiRequest constructor.
     */
    public function __construct() {
        
    }

    /**
     * Setup request
     *
     * @param $apiParams
     */
    abstract public function setup(array $apiParams): void;

    /**
     * Build request
     */
    abstract public function build(): void;

    /**
     * Pre-Execute callback
     */
    abstract public function preExecute(): void;

    /**
     * Post-Execute callback
     */
    abstract public function postExecute(): void;

    /**
     * Dynamiccaly hydrate request properties
     * 
     * @param array $properties
     */
    public function hydrate(array $properties): void {
        foreach ($properties as $key => $value) {
            $functionName = 'set'.ucwords($key);

            if (method_exists($this, $functionName)) {
                call_user_func_array([$this, $functionName], [$value]);
            }
        }
    }

    /**
     * @return string
     */
    public function getUri(): string {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint): void {
        $this->endpoint = $endpoint;
    }

    /**
     * @return array
     */
    public function getQuery(): ?array {
        return $this->query;
    }

    /**
     * @param array $query
     */
    public function setQuery(?array $query): void {
        $this->query = $query;
    }

    /**
     * @return
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * @param $body
     */
    public function setBody($body): void {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void {
        $this->headers = $headers;
    }

    /**
     * @return bool
     */
    public function isAsync(): bool {
        return $this->async;
    }

    /**
     * @param bool $async
     */
    public function setAsync(bool $async): void {
        $this->async = $async;
    }

    /**
     * @return bool
     */
    public function isSecured(): bool {
        return $this->secured;
    }

    /**
     * @param bool $secured
     */
    public function setSecured(bool $secured): void {
        $this->secured = $secured;
    }

    /**
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getHeadersParams(): array {
        return $this->headersParams;
    }

    /**
     * @param array $headersParams
     */
    public function setHeadersParams(array $headersParams): void {
        $this->headersParams = $headersParams;
    }

    /**
     * @return null
     */
    public function getCustomData() {
        return $this->customData;
    }

    /**
     * @param null $customData
     */
    public function setCustomData($customData): void {
        $this->customData = $customData;
    }

}
