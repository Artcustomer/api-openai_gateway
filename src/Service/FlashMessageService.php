<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author David
 */
class FlashMessageService
{

    private SessionInterface $session;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * @param string $type
     * @param mixed $message
     * @return void
     */
    public function addFlash(string $type, mixed $message): void
    {
        if ($this->session instanceof FlashBagAwareSessionInterface) {
            $this->session->getFlashBag()->add($type, $message);
        }
    }
}
