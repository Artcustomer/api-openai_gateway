<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

/**
 * @Route("/test")
 * 
 * @author David
 */
class TestController extends AbstractApiController {

    /**
     * @Route("/", name="test_index", methods={"GET"})
     * 
     * @return Response
     */
    public function index(Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getModelConnector()->list();

        return $this->responseProxy($response);
    }
}
