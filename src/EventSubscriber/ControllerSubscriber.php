<?php

namespace App\EventSubscriber;

use App\Controller\Application\AbstractApplicationController;
use App\Service\CacheService;
use App\Service\EdenAIService;
use App\Service\ElevenLabsService;
use App\Service\FlashMessageService;
use App\Service\OpenAIService;
use App\Utils\Consts\CacheConsts;
use Artcustomer\ApiUnit\Utils\ApiMethodTypes;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author David
 */
class ControllerSubscriber implements EventSubscriberInterface
{

    private Security $security;
    private CacheService $cache;
    private FlashMessageService $flashMessageService;
    private TranslatorInterface $translator;
    private OpenAIService $openAIService;
    private EdenAIService $edenAIService;
    private ElevenLabsService $elevenLabsService;
    private ControllerEvent $event;
    private $controller;

    /**
     * Constructor
     *
     * @param Security $security
     * @param CacheService $cache
     * @param FlashMessageService $flashMessageService
     * @param TranslatorInterface $translator
     * @param OpenAIService $openAIService
     * @param EdenAIService $edenAIService
     * @param ElevenLabsService $elevenLabsService
     */
    public function __construct(
        Security            $security,
        CacheService        $cache,
        FlashMessageService $flashMessageService,
        TranslatorInterface $translator,
        OpenAIService       $openAIService,
        EdenAIService       $edenAIService,
        ElevenLabsService   $elevenLabsService
    )
    {
        $this->security = $security;
        $this->cache = $cache;
        $this->flashMessageService = $flashMessageService;
        $this->translator = $translator;
        $this->openAIService = $openAIService;
        $this->edenAIService = $edenAIService;
        $this->elevenLabsService = $elevenLabsService;
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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        $this->event = $event;
        $this->controller = $controller;

        $this->handleApplication();

    }

    /**
     * @return void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function handleApplication(): void
    {
        if (
            $this->event->getRequest()->isMethod(ApiMethodTypes::GET) &&
            $this->controller instanceof AbstractApplicationController
        ) {
            $this->cache->delete(CacheConsts::DEBUG_API_CALLS);

            $this->checkApiTokens();
        }
    }

    /**
     * @return void
     */
    private function checkApiTokens(): void
    {
        if ($this->security->getUser() !== null) {
            if (!$this->openAIService->isApiKeyAvailable()) {
                $this->flashMessageService->addFlash(FlashMessageService::TYPE_NOTICE, $this->translator->trans('notice.no_openai_token_found'));
            }

            if (!$this->edenAIService->isApiKeyAvailable()) {
                $this->flashMessageService->addFlash(FlashMessageService::TYPE_NOTICE, $this->translator->trans('notice.no_edenai_token_found'));
            }

            if (!$this->elevenLabsService->isApiKeyAvailable()) {
                $this->flashMessageService->addFlash(FlashMessageService::TYPE_NOTICE, $this->translator->trans('notice.no_elevenlabs_token_found'));
            }
        }
    }
}
