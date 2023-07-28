<?php

namespace App\Service;

use App\Utils\Consts\SessionConsts;
use Artcustomer\OpenAIClient\OpenAIApiGateway;

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

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $organisation
     * @param bool $availability
     * @param SessionManager $sessionManager
     */
    public function __construct(string $apiKey, string $organisation, bool $availability, SessionManager $sessionManager)
    {
        $this->apiKey = $apiKey;
        $this->organisation = $organisation;
        $this->availability = $availability;
        $this->sessionManager = $sessionManager;

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
            $this->apiGateway = new OpenAIApiGateway($this->apiKey, $this->organisation, $this->availability);
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
     * @return bool
     */
    public function isTokenAvailable(): bool
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
        }
    }
}
