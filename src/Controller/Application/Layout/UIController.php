<?php

namespace App\Controller\Application\Layout;

use App\Service\CacheService;
use App\Service\NavigationService;
use App\Utils\Consts\CacheConsts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author David
 */
class UIController extends AbstractController
{

    private NavigationService $navigationService;
    private RequestStack $requestStack;
    private CacheService $cache;

    /**
     * Constructor
     *
     * @param NavigationService $navigationService
     * @param RequestStack $requestStack
     * @param CacheService $cache
     */
    public function __construct(NavigationService $navigationService, RequestStack $requestStack, CacheService $cache)
    {
        $this->navigationService = $navigationService;
        $this->requestStack = $requestStack;
        $this->cache = $cache;
    }

    /**
     * @return Response
     */
    public function breadcrumb(): Response
    {
        $mainRequest = $this->requestStack->getMainRequest();
        $currentRoute = $mainRequest->attributes->get('_route');

        return $this->render(
            'application/ui/breadcrumb.html.twig',
            [
                'paths' => $this->navigationService->getPath($currentRoute)
            ]
        );
    }

    /**
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function debugBarApi(): Response
    {
        $outputs = $this->cache->get(CacheConsts::DEBUG_API_CALLS, []);

        return $this->render(
            'application/ui/debug_bar_api.html.twig',
            [
                'outputs' => $outputs
            ]
        );
    }
}
