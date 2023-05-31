<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

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

        $response = $this->openAIService->getApiGateway()->getImageConnector()->create($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/edits", name="openai_images_edit", methods={"POST"})
     * 
     * @return Response
     */
    public function createEdit(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getImageConnector()->createEdit($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/variations", name="openai_images_variation", methods={"POST"})
     * 
     * @return Response
     */
    public function createImageVariation(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }
        
        $response = $this->openAIService->getApiGateway()->getImageConnector()->createVariation($params);

        return $this->responseProxy($response);
    }
}
