<?php

namespace App\Controller\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\AbstractApiController;

/**
 * @Route("/embeddings")
 * 
 * @author David
 */
class OpenAIEmbeddingsController extends AbstractApiController {

    /**
     * @Route("/create", name="openai_embeddings_create", methods={"POST"})
     * 
     * @return Response
     */
    public function create(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getEmbeddingConnector()->create($params);

        return $this->responseProxy($response);
    }
}
