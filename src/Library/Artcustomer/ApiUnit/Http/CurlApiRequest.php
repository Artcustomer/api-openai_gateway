<?php

namespace App\Library\Artcustomer\ApiUnit\Http;

class CurlApiRequest extends AbstractApiRequest {

    /**
     * @var \CurlHandle
     */
    private $curlResource = false;

    /**
     * CurlApiRequest constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Setup request
     *
     * @param $apiParams
     */
    public function setup(array $apiParams): void {
        
    }

    /**
     * Build request
     */
    public function build(): void {
        $this->buildHeaders();
        $this->buildOptions();
        $this->buildUri();
        $this->buildResource();
    }

    /**
     * Pre-Execute callback
     */
    public function preExecute(): void {
        
    }

    /**
     * Post-Execute callback
     */
    public function postExecute(): void {
        
    }

    /**
     * Generate Headers
     * @return array
     */
    protected function generateHeaders(): array {
        return [];
    }

    /**
     * Build headers
     */
    protected function buildHeaders(): void {
        
    }

    /**
     * Build options
     */
    protected function buildOptions(): void {
        
    }

    /**
     * Build uri
     */
    protected function buildUri(): void {
        
    }

    /**
     * Build resource
     */
    private function buildResource(): void {
        $this->curlResource = curl_init();
    }

    /**
     * @return \CurlHandle
     */
    public function getCurlResource(): \CurlHandle  {
        return $this->curlResource;
    }
    
    /**
     * @return string
     */
    public function getUri(): string {
        $uri = parent::getUri();
        
        if (!empty($this->query)) {
            $uri = sprintf('%s?%s', $uri, http_build_query($this->query));
        }

        return $uri;
    }

}
