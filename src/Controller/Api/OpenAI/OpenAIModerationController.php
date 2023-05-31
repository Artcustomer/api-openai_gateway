<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

/**
 * @Route("/moderations")
 * 
 * @author David
 */
class OpenAIModerationController extends AbstractApiController {

    /**
     * @Route("/create", name="openai_moderations_create", methods={"POST"})
     * 
     * @return Response
     */
    public function create(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getModerationConnector()->create($params);

        return $this->responseProxy($response);
    }
}
