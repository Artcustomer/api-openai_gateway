<?php

namespace App\Service;

use App\EventHandler\ApiEventHandler;
use App\Utils\Consts\SessionConsts;
use Artcustomer\ApiUnit\Enum\ClientConfig;
use Artcustomer\XAIClient\Client\ApiClient;
use Artcustomer\XAIClient\XAIApiGateway;

/**
 * @author David
 *
 * https://docs.x.ai/api
 */
class XAIService extends AbstractAPIClientService
{

    private ?XAIApiGateway $apiGateway = null;

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
     * Setup XAIApiGateway instance
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

            $this->apiGateway = new XAIApiGateway($this->apiKey, true);
            $this->apiGateway->setEventHandler(new ApiEventHandler($this->eventDispatcher));
            $this->apiGateway->setClientConfig($config);
            $this->apiGateway->initialize();
        }
    }

    /**
     * Get XAIApiGateway instance
     *
     * @return XAIApiGateway
     * @throws \ReflectionException
     */
    public function getApiGateway(): XAIApiGateway
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
        $this->sessionManager->set(SessionConsts::XAI_API_KEY, $apiKey);

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
            $this->apiKey = $this->sessionManager->get(SessionConsts::XAI_API_KEY, '');
            $this->apiKeyInEnv = false;
            $this->apiKeyInSession = true;
        } else {
            $this->apiKeyInEnv = true;
            $this->apiKeyInSession = false;
        }
    }
}
