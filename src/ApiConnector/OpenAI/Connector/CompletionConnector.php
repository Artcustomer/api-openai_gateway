<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\CompletionRequest;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class CompletionConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Creates a completion
     */
    public function create(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(CompletionRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
