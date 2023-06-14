<?php

namespace App\Controller;

use App\Service\JsonUserService;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author David
 */
class MainController extends AbstractController
{

    /**
     * @Route("/", name="main_index", methods={"GET"})
     *
     * @return Response
     */
    public function index(JsonUserService $jsonUserService): Response
    {
        return $this->render(
            'main/index.html.twig',
            []
        );
    }
}
