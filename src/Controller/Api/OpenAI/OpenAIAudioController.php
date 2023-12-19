<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/audio")
 *
 * @author David
 */
class OpenAIAudioController extends AbstractApiController
{

    /**
     * @Route("/create_transcription", name="openai_audio_createtranscription", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function createTranscription(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranscription($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/create_translation", name="openai_audio_createtranslation", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function createTranslation(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranslation($params);

        return $this->responseProxy($response);
    }
}
