<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/models")
 *
 * @author David
 */
class OpenAIModelController extends AbstractApiController
{

    /**
     * @Route("/", name="openai_models_getall", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function getAll(Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getModelConnector()->list();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{modelid}", name="openai_models_getone", requirements={"modelid"="[a-z0-9]+"}, methods={"GET"})
     *
     * @param string $modelid
     * @param Request $request
     * @return Response
     */
    public function getOne(string $modelid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getModelConnector()->get($modelid);

        return $this->responseProxy($response);
    }
}
