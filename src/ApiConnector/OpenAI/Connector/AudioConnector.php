<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\AudioRequest;
use App\ApiConnector\OpenAI\Utils\ApiEndpoints;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class AudioConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Transcribes audio into the input language
     */
    public function createTranscription(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::TRANSCRIPTIONS,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(AudioRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Translates audio into into English
     */
    public function createTranslation(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => ApiEndpoints::TRANSLATIONS,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(AudioRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
