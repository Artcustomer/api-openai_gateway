<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\FineTunesRequest;
use App\ApiConnector\OpenAI\Utils\ApiEndpoints;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class FineTuneConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Creates a job that fine-tunes a specified model from a given dataset
     */
    public function create(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(FineTunesRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * List your organization's fine-tuning jobs
     */
    public function list(): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET
        ];
        $request = $this->client->getRequestFactory()->instantiate(FineTunesRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Gets info about the fine-tune job
     */
    public function get(string $fineTuneId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => $fineTuneId
        ];
        $request = $this->client->getRequestFactory()->instantiate(FineTunesRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Immediately cancel a fine-tune job
     */
    public function cancel(string $fineTuneId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'endpoint' => sprintf('%s/%s', $fineTuneId, ApiEndpoints::CANCEL)
        ];
        $request = $this->client->getRequestFactory()->instantiate(FineTunesRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Get fine-grained status updates for a fine-tune job
     */
    public function listEvents(string $fineTuneId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => sprintf('%s/%s', $fineTuneId, ApiEndpoints::EVENTS)
        ];
        $request = $this->client->getRequestFactory()->instantiate(FineTunesRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Delete a fine-tuned model
     */
    public function deleteModel(string $fineTuneId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::DELETE,
            'endpoint' => $fineTuneId
        ];
        $request = $this->client->getRequestFactory()->instantiate(FineTunesRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
