<?php

namespace App\Service;

use App\EventHandler\ApiEventHandler;
use App\Utils\Consts\SessionConsts;
use Artcustomer\ApiUnit\Enum\ClientConfig;
use Artcustomer\OpenAIClient\Client\ApiClient;
use Artcustomer\OpenAIClient\OpenAIApiGateway;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David
 *
 * https://beta.openai.com/docs/api-reference/authentication
 */
class OpenAIService
{

    private ?OpenAIApiGateway $apiGateway = null;

    private string $apiKey = '';
    private string $organisation;
    private bool $availability;
    private SessionManager $sessionManager;
    private EventDispatcherInterface $eventDispatcher;
    private bool $apiKeyInEnv = false;
    private bool $apiKeyInSession = false;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $organisation
     * @param bool $availability
     * @param SessionManager $sessionManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(string $apiKey, string $organisation, bool $availability, SessionManager $sessionManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->apiKey = $apiKey;
        $this->organisation = $organisation;
        $this->availability = $availability;
        $this->sessionManager = $sessionManager;
        $this->eventDispatcher = $eventDispatcher;

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

            $this->apiGateway = new OpenAIApiGateway($this->apiKey, $this->organisation, $this->availability);
            $this->apiGateway->setEventHandler(new ApiEventHandler($this->eventDispatcher));
            $this->apiGateway->setClientConfig($config);
            $this->apiGateway->initialize();
        }
    }

    /**
     * Get OpenAIApiGateway instance
     *
     * @return OpenAIApiGateway
     * @throws \ReflectionException
     */
    public function getApiGateway(): OpenAIApiGateway
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
        $this->sessionManager->set(SessionConsts::OPENAI_API_KEY, $apiKey);

        $this->apiKey = $apiKey;
        $this->apiKeyInSession = true;
    }

    /**
     * @return bool
     */
    public function isApiKeyInEnv(): bool
    {
        return $this->apiKeyInEnv;
    }

    /**
     * @return bool
     */
    public function isApiKeyInSession(): bool
    {
        return $this->apiKeyInSession;
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
            $this->apiKey = $this->sessionManager->get(SessionConsts::OPENAI_API_KEY, '');
            $this->apiKeyInEnv = false;
            $this->apiKeyInSession = true;
        } else {
            $this->apiKeyInEnv = true;
            $this->apiKeyInSession = false;
        }
    }
}
