<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\EngineRequest;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class EngineConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Lists the currently available (non-finetuned) engines
     */
    public function list(): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET
        ];
        $request = $this->client->getRequestFactory()->instantiate(EngineRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Retrieves an engine instance
     */
    public function get(string $engineId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => $engineId
        ];
        $request = $this->client->getRequestFactory()->instantiate(EngineRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
