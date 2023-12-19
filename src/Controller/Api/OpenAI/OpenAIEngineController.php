<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/engines")
 *
 * @author David
 */
class OpenAIEngineController extends AbstractApiController
{

    /**
     * @Route("/", name="openai_engines_getall", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function getAll(Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getEngineConnector()->list();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{engineid}", name="openai_engines_getone", requirements={"engineid"="[a-z0-9]+"}, methods={"GET"})
     *
     * @param string $engineid
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function getOne(string $engineid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getEngineConnector()->get($engineid);

        return $this->responseProxy($response);
    }
}
