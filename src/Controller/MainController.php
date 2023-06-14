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
        $data = new stdClass();
        $data->username = 'test';
        $data->password = 'test';
        $data->firstName = 'John';
        $data->lastName = 'Doe';
        $data->description = 'Test user';
        $data->roles = ['ROLE_APP'];

        //$result = $jsonUserService->addUser($data);
        $result = $jsonUserService->removeUser(3);
        dump($result);

        $all = $jsonUserService->getUsers();
        dump($all);
        exit;

        return $this->render(
            'main/index.html.twig',
            []
        );
    }
}
