<?php

namespace App\Service;

use App\EventHandler\ApiEventHandler;
use Artcustomer\ApiUnit\Enum\ClientConfig;
use Artcustomer\ElevenLabsClient\Client\ApiClient;
use Artcustomer\ElevenLabsClient\ElevenLabsApiGateway;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David
 *
 * https://elevenlabs.io/docs/api-reference/text-to-speech
 */
class ElevenLabsService
{

    private ?ElevenLabsApiGateway $apiGateway = null;

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

            $this->apiGateway = new ElevenLabsApiGateway($this->apiKey);
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
     * Get ElevenLabsApiGateway instance
     *
     * @return ElevenLabsApiGateway
     * @throws \ReflectionException
     */
    public function getApiGateway(): ElevenLabsApiGateway
    {
        $this->setupApiGateway();

        return $this->apiGateway;
    }
}
