<?php

namespace App\Service;

use Artcustomer\EdenAIClient\EdenAIApiGateway;

/**
 * @author David
 *
 * https://docs.edenai.co/reference/start-your-ai-journey-with-edenai
 */
class EdenAIService
{

    private ?EdenAIApiGateway $apiGateway = null;

    private string $apiKey = '';

    /**
     * Constructor
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Setup OpenAIApiGateway instance
     *
     * @return void
     * @throws \ReflectionException
     */
    private function setupApiGateway(): void
    {
        if ($this->apiGateway === null) {
            $this->apiGateway = new EdenAIApiGateway($this->apiKey);
            $this->apiGateway->initialize();
        }
    }

    /**
     * Get EdenAIApiGateway instance
     *
     * @return EdenAIApiGateway
     * @throws \ReflectionException
     */
    public function getApiGateway(): EdenAIApiGateway
    {
        $this->setupApiGateway();

        return $this->apiGateway;
    }
}
