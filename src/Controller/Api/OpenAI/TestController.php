<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 *
 * @author David
 */
class TestController extends AbstractApiController
{

    /**
     * @Route("/", name="test_index", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function index(Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getModelConnector()->list();

        return $this->responseProxy($response);
    }
}
