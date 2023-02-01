<?php

namespace App\Controller\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\AbstractApiController;

/**
 * @Route("/images")
 * 
 * @author David
 */
class OpenAIImageController extends AbstractApiController {

    /**
     * @Route("/generations", name="openai_images_create", methods={"POST"})
     * 
     * @return Response
     */
    public function create(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->createImage(
            $params['prompt'] ?? '',
            $params['n'] ?? 1,
            $params['size'] ?? '1024x1024',
            $params['response_format'] ?? 'url',
            $params['user'] ?? ''
        );

        return $this->responseProxy($response);
    }

    /**
     * @Route("/edits", name="openai_images_edit", methods={"POST"})
     * 
     * @return Response
     */
    public function createEdit(Request $request): Response {
        $response = $this->openAIService->getApiGateway()->createEdit();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/variations", name="openai_images_variation", methods={"POST"})
     * 
     * @return Response
     */
    public function createVariation(Request $request): Response {
        $response = $this->openAIService->getApiGateway()->createVariation();

        return $this->responseProxy($response);
    }
}
