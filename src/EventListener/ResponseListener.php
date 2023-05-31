<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ResponseListener
{
    public function __invoke(ResponseEvent $event): void
    {
        //dump($event);
        //dump($event->getRequest()->attributes->get('_route'));
        //exit;
    }
}