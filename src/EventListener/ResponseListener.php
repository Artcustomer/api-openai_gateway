<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{

    /**
     * @param ResponseEvent $event
     * @return void
     */
    public function __invoke(ResponseEvent $event): void
    {

    }
}
