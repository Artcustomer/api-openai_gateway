<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author David
 */
class MainController extends AbstractController {

    /**
     * @Route("/", name="main_index", methods={"GET"})
     * 
     * @return Response
     */
    public function index(RouterInterface $router): Response {
        $routes = $router->getRouteCollection()->all();
        $routeOutput = [];

        array_walk(
            $routes,
            function ($item) use (&$routeOutput) {
                $path = $item->getPath();

                if (0 === strpos($path, '/api')) {
                    $routeOutput[] = $path;
                }
            }
        );

        return new Response(implode('<br />', $routeOutput));
    }

}
