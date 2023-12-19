<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/embeddings")
 *
 * @author David
 */
class OpenAIEmbeddingsController extends AbstractApiController
{

    /**
     * @Route("/create", name="openai_embeddings_create", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function create(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getEmbeddingConnector()->create($params);

        return $this->responseProxy($response);
    }
}
