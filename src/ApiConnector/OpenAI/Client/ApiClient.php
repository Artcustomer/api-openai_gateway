<?php

namespace App\ApiConnector\OpenAI\Client;

use Artcustomer\ApiUnit\Client\CurlApiClient;
use App\ApiConnector\OpenAI\Utils\ApiInfos;
use App\ApiConnector\OpenAI\Utils\ApiTools;
use App\ApiConnector\OpenAI\Http\ApiRequest;
use App\ApiConnector\OpenAI\Factory\Decorator\ResponseDecorator;

/**
 * @author David
 */
class ApiClient extends CurlApiClient {

    /**
     * Constructor
     * 
     * This client is configured to return response as objects.
     * It helps to manipulate data before encoding items to json format.
     * 
     * @param array $params
     * @param bool $useDecorator
     */
    public function __construct(array $params, bool $useDecorator = false) {
        parent::__construct($params);

        if ($useDecorator) {
            $this->responseDecoratorClassName = ResponseDecorator::class;
            $this->responseDecoratorArguments = [ApiTools::CONTENT_TYPE_OBJECT];
        }

        $this->enableListeners = TRUE;
        $this->enableEvents = FALSE;
        $this->enableMocks = FALSE;
        $this->debugMode = FALSE;
    }

    /**
     * Initialize client
     */
    public function initialize(): void {
        $this->init();
        $this->checkParams();
    }

    /**
     * Pre build request
     * 
     * @param string $method
     * @param string $endpoint
     * @param array $query
     * @param type $body
     * @param array $headers
     * @return void
     */
    protected function preBuildRequest(string $method, string $endpoint, array $query = [], $body = null, array $headers = []): void {
        $this->requestClassName = ApiRequest::class;
    }

    /**
     * Check parameters
     * 
     * @throws \Exception
     */
    private function checkParams() {
        $requiredParams = ['protocol', 'host', 'version'];

        foreach ($requiredParams as $param) {
            if (!isset($this->apiParams[$param])) {
                $this->isOperational = false;
            }
        }

        if (!$this->isOperational) {
            throw new \Exception(sprintf('%s : Missing params', ApiInfos::API_NAME), 500);
        }
    }
}
