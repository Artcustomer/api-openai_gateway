<?php

namespace App\Service;

use App\EventHandler\ApiEventHandler;
use App\Utils\Consts\SessionConsts;
use Artcustomer\ApiUnit\Enum\ClientConfig;
use Artcustomer\EdenAIClient\Client\ApiClient;
use Artcustomer\EdenAIClient\EdenAIApiGateway;

/**
 * @author David
 *
 * https://docs.edenai.co/reference/start-your-ai-journey-with-edenai
 */
class EdenAIService extends AbstractAPIClientService
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
     * @return void
     */
    public function initialize(): void
    {
        $this->defineApiKey();
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

    /**
     * @param string $apiKey
     * @return void
     */
    public function setApiKeyInSession(string $apiKey): void
    {
        $this->sessionManager->set(SessionConsts::EDENAI_API_KEY, $apiKey);

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
            $this->apiKey = $this->sessionManager->get(SessionConsts::EDENAI_API_KEY, '');
            $this->apiKeyInEnv = false;
            $this->apiKeyInSession = true;
        } else {
            $this->apiKeyInEnv = true;
            $this->apiKeyInSession = false;
        }
    }
}
