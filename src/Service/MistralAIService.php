<?php

namespace App\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David
 *
 * https://docs.mistral.ai/api/
 */
class MistralAIService
{

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
     * @return bool
     */
    public function isApiKeyAvailable(): bool
    {
        return !empty($this->apiKey);
    }
}
