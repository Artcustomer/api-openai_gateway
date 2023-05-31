<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

/**
 * @Route("/engines")
 * 
 * @author David
 */
class OpenAIEngineController extends AbstractApiController {

    /**
     * @Route("/", name="openai_engines_getall", methods={"GET"})
     * 
     * @return Response
     */
    public function getAll(Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getEngineConnector()->list();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{engineid}", name="openai_engines_getone", requirements={"engineid"="[a-z0-9]+"}, methods={"GET"})
     * 
     * @return Response
     */
    public function getOne(string $engineid, Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getEngineConnector()->get($engineid);

        return $this->responseProxy($response);
    }
}
