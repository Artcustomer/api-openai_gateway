<?php

namespace App\Controller\Application\Layout;

use App\Service\NavigationService;
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
}
