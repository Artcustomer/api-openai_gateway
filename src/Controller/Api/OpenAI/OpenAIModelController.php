<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

/**
 * @Route("/models")
 * 
 * @author David
 */
class OpenAIModelController extends AbstractApiController {

    /**
     * @Route("/", name="openai_models_getall", methods={"GET"})
     * 
     * @return Response
     */
    public function getAll(Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getModelConnector()->list();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{modelid}", name="openai_models_getone", requirements={"modelid"="[a-z0-9]+"}, methods={"GET"})
     * 
     * @return Response
     */
    public function getOne(string $modelid, Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getModelConnector()->get($modelid);

        return $this->responseProxy($response);
    }
}
