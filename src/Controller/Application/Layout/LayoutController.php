<?php

namespace App\Controller\Application\Layout;

use App\Service\NavigationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author David
 */
class LayoutController extends AbstractController
{

    private NavigationService $navigationService;
    private RequestStack $requestStack;

    /**
     * @param NavigationService $navigationService
     * @param RequestStack $requestStack
     */
    public function __construct(NavigationService $navigationService, RequestStack $requestStack)
    {
        $this->navigationService = $navigationService;
        $this->requestStack = $requestStack;
    }

    /**
     * @return Response
     */
    public function header(): Response
    {
        $mainRequest = $this->requestStack->getMainRequest();
        $currentRoute = $mainRequest->attributes->get('_route');

        return $this->render(
            'application/header.html.twig',
            [
                'userMenuData' => $this->navigationService->getUserMenu($currentRoute),
            ]
        );
    }

    /**
     * @return Response
     */
    public function footer(): Response
    {
        return $this->render(
            'application/footer.html.twig',
            []
        );
    }

    /**
     * @return Response
     */
    public function sidebar(): Response
    {
        $mainRequest = $this->requestStack->getMainRequest();
        $currentRoute = $mainRequest->attributes->get('_route');

        return $this->render(
            'application/sidebar.html.twig',
            [
                'navigationData' => $this->navigationService->getNavigation($currentRoute),
            ]
        );
    }
}
