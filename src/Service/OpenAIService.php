<?php

namespace App\Service;

use Artcustomer\OpenAIClient\OpenAIApiGateway;

/**
 * @author David
 *
 * https://beta.openai.com/docs/api-reference/authentication
 */
class OpenAIService
{

    private OpenAIApiGateway $apiGateway;

    private string $apiKey;
    private string $organisation;
    private bool $availability;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $organisation
     * @param bool $availability
     * @throws \ReflectionException
     */
    public function __construct(string $apiKey, string $organisation, bool $availability)
    {
        $this->apiKey = $apiKey;
        $this->organisation = $organisation;
        $this->availability = $availability;

        $this->setupApiGateway();
    }

    /**
     * Setup OpenAIApiGateway instance
     *
     * @return void
     * @throws \ReflectionException
     */
    private function setupApiGateway(): void
    {
        $this->apiGateway = new OpenAIApiGateway($this->apiKey, $this->organisation, $this->availability);
        $this->apiGateway->initialize();
    }

    /**
     * Get OpenAIApiGateway instance
     *
     * @return OpenAIApiGateway
     */
    public function getApiGateway(): OpenAIApiGateway
    {
        return $this->apiGateway;
    }
}
