<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\ModelRequest;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class ModelConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Lists the currently available models.
     */
    public function list(): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET
        ];
        $request = $this->client->getRequestFactory()->instantiate(ModelRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Retrieves a model instance
     */
    public function get(string $modelId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => $modelId
        ];
        $request = $this->client->getRequestFactory()->instantiate(ModelRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
