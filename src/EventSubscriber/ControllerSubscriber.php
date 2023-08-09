<?php

namespace App\EventSubscriber;

use App\Controller\Application\AbstractApplicationController;
use App\Service\FlashMessageService;
use App\Service\OpenAIService;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ControllerSubscriber implements EventSubscriberInterface
{

    private Security $security;
    private FlashMessageService $flashMessageService;
    private TranslatorInterface $translator;
    private OpenAIService $openAIService;
    private ControllerEvent $event;
    private $controller;

    /**
     * @param Security $security
     * @param FlashMessageService $flashMessageService
     * @param TranslatorInterface $translator
     * @param OpenAIService $openAIService
     */
    public function __construct(Security $security, FlashMessageService $flashMessageService, TranslatorInterface $translator, OpenAIService $openAIService)
    {
        $this->security = $security;
        $this->flashMessageService = $flashMessageService;
        $this->translator = $translator;
        $this->openAIService = $openAIService;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param ControllerEvent $event
     * @return void
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        $this->event = $event;
        $this->controller = $controller;

        $this->checkOpenAIApiToken();
    }

    /**
     * @return void
     */
    private function checkOpenAIApiToken(): void
    {
        if (
            $this->event->getRequest()->isMethod(ApiMethodTypes::GET) &&
            $this->controller instanceof AbstractApplicationController
        ) {
            if ($this->security->getUser() !== null) {
                $isApiKeyAvailable = $this->openAIService->isApiKeyAvailable();

                if (!$isApiKeyAvailable) {
                    $this->flashMessageService->addFlash('notice', $this->translator->trans('notice.no_token_found'));
                }
            }
        }
    }
}
