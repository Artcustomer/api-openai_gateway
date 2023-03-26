<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\ImageRequest;
use App\ApiConnector\OpenAI\Utils\ApiEndpoints;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class ImageConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Creates an image
     */
    public function create(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::GENERATIONS,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(ImageRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Creates an edited or extended image
     */
    public function createEdit(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::EDITS,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(ImageRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Creates a variation of a given image
     */
    public function createVariation(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::VARIATIONS,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(ImageRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
