<?php

namespace App\Service;

use App\EventHandler\ApiEventHandler;
use App\Utils\Consts\SessionConsts;
use Artcustomer\ApiUnit\Enum\ClientConfig;
use Artcustomer\GeminiClient\Client\ApiClient;
use Artcustomer\GeminiClient\GeminiApiGateway;

/**
 * @author David
 *
 * https://ai.google.dev/gemini-api/docs
 */
class GeminiService extends AbstractAPIClientService
{

    private ?GeminiApiGateway $apiGateway = null;

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
     * @return void
     */
    public function initialize(): void
    {
        $this->defineApiKey();
    }

    /**
     * Setup GeminiApiGateway instance
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

            $this->apiGateway = new GeminiApiGateway($this->apiKey, true);
            $this->apiGateway->setEventHandler(new ApiEventHandler($this->eventDispatcher));
            $this->apiGateway->setClientConfig($config);
            $this->apiGateway->initialize();
        }
    }

    /**
     * Get GeminiApiGateway instance
     *
     * @return GeminiApiGateway
     * @throws \ReflectionException
     */
    public function getApiGateway(): GeminiApiGateway
    {
        $this->setupApiGateway();

        return $this->apiGateway;
    }

    /**
     * @param string $apiKey
     * @return void
     */
    public function setApiKeyInSession(string $apiKey): void
    {
        $this->sessionManager->set(SessionConsts::GEMINI_API_KEY, $apiKey);

        $this->apiKey = $apiKey;
        $this->apiKeyInSession = true;
    }

    /**
     * @return bool
     */
    public function isApiKeyAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * @return void
     */
    private function defineApiKey(): void
    {
        if (empty($this->apiKey)) {
            $this->apiKey = $this->sessionManager->get(SessionConsts::GEMINI_API_KEY, '');
            $this->apiKeyInEnv = false;
            $this->apiKeyInSession = true;
        } else {
            $this->apiKeyInEnv = true;
            $this->apiKeyInSession = false;
        }
    }
}
