<?php

namespace App\ApiConnector\OpenAI\Connector;

use App\ApiConnector\OpenAI\Http\FileRequest;
use App\ApiConnector\OpenAI\Utils\ApiEndpoints;
use Artcustomer\ApiUnit\Client\AbstractApiClient;
use Artcustomer\ApiUnit\Connector\AbstractConnector;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;

/**
 * @author David
 */
class FileConnector extends AbstractConnector {

    public function __construct(AbstractApiClient $client) {
        parent::__construct($client, FALSE);
    }

    /**
     * Returns a list of files that belong to the user's organization
     */
    public function list(): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET
        ];
        $request = $this->client->getRequestFactory()->instantiate(FileRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Upload a file that contains document(s) to be used across various endpoints/features
     */
    public function upload(array $params): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::POST,
            'body' => $params
        ];
        $request = $this->client->getRequestFactory()->instantiate(FileRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Delete a file
     */
    public function delete(string $fileId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::DELETE,
            'endpoint' => $fileId
        ];
        $request = $this->client->getRequestFactory()->instantiate(FileRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Returns information about a specific file
     */
    public function get(string $fileId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => $fileId
        ];
        $request = $this->client->getRequestFactory()->instantiate(FileRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }

    /**
     * Returns the contents of the specified file
     */
    public function getContent(string $fileId): IApiResponse {
        $data = [
            'method' => ApiMethodTypes::GET,
            'endpoint' => sprintf('%s/%s', $fileId, ApiEndpoints::CONTENT)
        ];
        $request = $this->client->getRequestFactory()->instantiate(FileRequest::class, [$data]);

        return $this->client->executeRequest($request);
    }
}
