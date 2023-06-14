<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/completions")
 *
 * @author David
 */
class OpenAICompletionController extends AbstractApiController
{

    /**
     * @Route("/create", name="openai_completions_create", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getCompletionConnector()->create($params);

        return $this->responseProxy($response);
    }
}
