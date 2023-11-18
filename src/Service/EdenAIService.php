<?php

namespace App\Service;

use App\EventHandler\ApiEventHandler;
use Artcustomer\ApiUnit\Enum\ClientConfig;
use Artcustomer\EdenAIClient\Client\ApiClient;
use Artcustomer\EdenAIClient\EdenAIApiGateway;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David
 *
 * https://docs.edenai.co/reference/start-your-ai-journey-with-edenai
 */
class EdenAIService
{

    private ?EdenAIApiGateway $apiGateway = null;

    private string $apiKey = '';
    private EventDispatcherInterface $eventDispatcher;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(string $apiKey, EventDispatcherInterface $eventDispatcher)
    {
        $this->apiKey = $apiKey;
        $this->eventDispatcher = $eventDispatcher;
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
            $config = [
                ApiClient::CONFIG_USE_DECORATOR => true,
                ClientConfig::ENABLE_EVENTS => true,
                ClientConfig::DEBUG_MODE => false
            ];

            $this->apiGateway = new EdenAIApiGateway($this->apiKey);
            $this->apiGateway->setEventHandler(new ApiEventHandler($this->eventDispatcher));
            $this->apiGateway->setClientConfig($config);
            $this->apiGateway->initialize();
        }
    }

    /**
     * @return bool
     */
    public function isApiKeyAvailable(): bool
    {
        return !empty($this->apiKey);
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
