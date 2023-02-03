<?php

namespace App\ApiConnector\OpenAI;

use Artcustomer\ApiUnit\Gateway\AbstractApiGateway;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;
use App\ApiConnector\OpenAI\Client\ApiClient;
use App\ApiConnector\OpenAI\Http\CompletionRequest;
use App\ApiConnector\OpenAI\Http\EngineRequest;
use App\ApiConnector\OpenAI\Http\ImageRequest;
use App\ApiConnector\OpenAI\Http\ModelRequest;
use App\ApiConnector\OpenAI\Utils\ApiEndpoints;
use App\ApiConnector\OpenAI\Utils\ApiInfos;

/**
 * @author David
 */
class OpenAIApiGateway extends AbstractApiGateway {

    private string $apiKey;
    private string $organisation;

    /**
     * Constructor
     */
    public function __construct(string $apiKey, string $organisation) {
        $this->apiKey = $apiKey;
        $this->organisation = $organisation;

        $this->defineParams();

        parent::__construct(ApiClient::class, [$this->params]);
    }

    /**
     * Initialize
     * 
     * @return void
     */
    public function initialize(): void {
        $this->client->initialize();
    }

    /**
     * Test API
     * 
     * @return IApiResponse
     */
    public function test(): IApiResponse {
        return $this->getModels();
    }

    public function getModels(): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET
        ];
        $request = $this->client->getRequestFactory()->instantiate(ModelRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    public function getModel(string $modelId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => $modelId
        ];
        $request = $this->client->getRequestFactory()->instantiate(ModelRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    public function getEngines(): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET
        ];
        $request = $this->client->getRequestFactory()->instantiate(EngineRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    public function getEngine(string $engineId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => $engineId
        ];
        $request = $this->client->getRequestFactory()->instantiate(EngineRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    public function createCompletion(array $params/*string $model, string|array $prompt, string $suffix = null, int $maxTokens = 16*/): IApiResponse {
        $body = [
            'model' => $params['model'],
            'prompt' => $params['prompt']
        ];
        $data = [
            'method' => ApiMethodTypes::POST,
            'body' => $body
        ];
        $request = $this->client->getRequestFactory()->instantiate(CompletionRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    public function createImage(string $prompt, int $num = 1, string $size = '1024x1024', string $responseFormat = 'url', string $user = ''): IApiResponse {
        $body = [
            'prompt' => $prompt,
            'n' => $num,
            'size' => $size,
            'response_format' => $responseFormat
        ];

        if (!empty($user)) {
            $body['user'] = $user;
        }

        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::GENERATIONS,
            'body' => $body
        ];
        $request = $this->client->getRequestFactory()->instantiate(ImageRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Define parameters
     * 
     * @return void
     */
    private function defineParams(): void {
        $this->params['api_name'] = ApiInfos::API_NAME;
        $this->params['api_version'] = ApiInfos::API_VERSION;
        $this->params['protocol'] = ApiInfos::PROTOCOL;
        $this->params['host'] = ApiInfos::HOST;
        $this->params['version'] = ApiInfos::VERSION;
        $this->params['api_key'] = $this->apiKey;
        $this->params['organisation'] = $this->organisation;
    }
}