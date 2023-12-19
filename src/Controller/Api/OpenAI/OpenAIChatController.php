<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chat")
 *
 * @author David
 */
class OpenAIChatController extends AbstractApiController
{

    /**
     * @Route("/create_completion", name="openai_chat_createcompletion", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function createCompletion(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($params);

        return $this->responseProxy($response);
    }
}
