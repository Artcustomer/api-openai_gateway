<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

/**
 * @Route("/audio")
 * 
 * @author David
 */
class OpenAIAudioController extends AbstractApiController {

    /**
     * @Route("/create_transcription", name="openai_audio_createtranscription", methods={"POST"})
     * 
     * @return Response
     */
    public function createTranscription(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranscription($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/create_translation", name="openai_audio_createtranslation", methods={"POST"})
     * 
     * @return Response
     */
    public function createTranslation(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranslation($params);

        return $this->responseProxy($response);
    }
}
