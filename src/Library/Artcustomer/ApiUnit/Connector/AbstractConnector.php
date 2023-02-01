<?php

namespace App\Library\Artcustomer\ApiUnit\Connector;

use App\Library\Artcustomer\ApiUnit\Client\AbstractApiClient;

/**
 * @author David
 */
abstract class AbstractConnector {

    protected AbstractApiClient $client;
    
    public function __construct(AbstractApiClient $client, bool $initializeClient = TRUE) {
        $this->client = $client;

        if ($initializeClient) {
            $this->client->initialize();
        }
    }

    public function getClient(): AbstractApiClient {
        return $this->client;
    }
}
