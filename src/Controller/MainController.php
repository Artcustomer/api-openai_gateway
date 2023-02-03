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
    public function index(): Response {
        return $this->render(
            'home/index.html.twig',
            []
        );
    }

}
