<?php

namespace App\ApiConnector\OpenAI\Http;

use App\Library\Artcustomer\ApiUnit\Http\CurlApiRequest;

/**
 * @author David
 */
class ApiRequest extends CurlApiRequest {

    private string $protocol;
    private string $host;
    private string $version;
    private string $apiKey;
    private string $organisation;
    protected string $uriBase;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->secured = TRUE;
    }

    /**
     * Setup request
     *
     * @param array $apiParams
     * @return void
     */
    public function setup(array $apiParams): void {
        if (array_key_exists('protocol', $apiParams)) {
            $this->protocol = $apiParams['protocol'];
        }

        if (array_key_exists('host', $apiParams)) {
            $this->host = $apiParams['host'];
        }

        if (array_key_exists('version', $apiParams)) {
            $this->version = $apiParams['version'];
        }

        if (array_key_exists('api_key', $apiParams)) {
            $this->apiKey = $apiParams['api_key'];
        }

        if (array_key_exists('organisation', $apiParams)) {
            $this->organisation = $apiParams['organisation'];
        }

        $this->uriBase = sprintf('%s%s/%s', $this->protocol, $this->host, $this->version);
    }

    /**
     * PreExecute callback
     */
    public function preExecute(): void {
        $this->body = json_encode($this->body);
    }

    /**
     * PostExecute callback
     */
    public function postExecute(): void {
        
    }

    /**
     * Build options
     */
    protected function buildOptions(): void {
        $this->options[CURLOPT_SSL_VERIFYHOST] = 0;
        $this->options[CURLOPT_SSL_VERIFYPEER] = 0;
        $this->options[CURLOPT_HEADER] = 0;
        $this->options[CURLOPT_ENCODING] = '';
        $this->options[CURLOPT_RETURNTRANSFER] = 1;
        $this->options[CURLOPT_FOLLOWLOCATION] = 1;
        $this->options[CURLOPT_MAXREDIRS] = 10;
        $this->options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
    }

    /**
     * Build headers
     */
    protected function buildHeaders(): void {
        $this->headers['Content-Type'] = 'application/json';
        $this->headers['Authorization'] = sprintf('Bearer %s', $this->apiKey);

        if (!empty($this->organisation)) {
            $this->headers['OpenAI-Organization'] = $this->organisation;
        }
    }

    /**
	 * Build Uri
	 */
	protected function buildUri(): void {
        $this->uri = sprintf('%s/%s', $this->uriBase, $this->endpoint);
    }

}
