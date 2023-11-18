<?php

namespace App\EventHandler;

use App\Event\ApiCallEvent;
use App\Service\CacheService;
use Artcustomer\ApiUnit\Event\IApiEvent;
use Artcustomer\ApiUnit\Event\IApiEventHandler;
use Artcustomer\ApiUnit\Utils\ApiEventTypes;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David
 */
class ApiEventHandler implements IApiEventHandler
{

    protected EventDispatcherInterface $eventDispatcher;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param CacheService $cache
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param IApiEvent $event
     * @return void
     */
    public function handleEvent(IApiEvent $event): void
    {
        switch ($event->getName()) {
            case ApiEventTypes::POST_EXECUTE:
                $event = new ApiCallEvent($event->getRequest(), $event->getResponse());

                $this->eventDispatcher->dispatch($event, ApiCallEvent::EVENT_API_CALL_POST_EXECUTE);
                break;
            default:
                break;
        }
    }
}
