<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\ChatRequest;
use App\ApiConnector\OpenAI\Utils\ApiEndpoints;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class ChatConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Creates a completion for the chat message
     */
    public function createCompletion(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::COMPLETIONS,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(ChatRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
