<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @author David
 */
class RequestListener
{

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {

    }
}
