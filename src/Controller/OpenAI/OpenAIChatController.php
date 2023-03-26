<?php

namespace App\Controller\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\AbstractApiController;

/**
 * @Route("/chat")
 * 
 * @author David
 */
class OpenAIChatController extends AbstractApiController {

    /**
     * @Route("/create_completion", name="openai_chat_createcompletion", methods={"POST"})
     * 
     * @return Response
     */
    public function createCompletion(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($params);

        return $this->responseProxy($response);
    }
}
