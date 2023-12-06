<?php

namespace App\Controller\Application;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author David
 */
class HomeController extends AbstractApplicationController
{

    /**
     * @Route("/", name="application_home_index", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'application/home.html.twig',
            []
        );
    }
}
