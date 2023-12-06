<?php

namespace App\Controller\Application;

use App\Form\Type\ElevenLabs\AudioTextToSpeechCreateType;
use App\Service\ElevenLabsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/elevenlabs")
 *
 * @author David
 */
class ElevenLabsController extends AbstractApplicationController
{

    protected ElevenLabsService $elevenLabsService;

    /**
     * Constructor
     *
     * @param ElevenLabsService $elevenLabsService
     */
    public function __construct(ElevenLabsService $elevenLabsService)
    {
        $this->elevenLabsService = $elevenLabsService;
    }

    /**
     * @Route("/text-to-speech", name="application_elevenlabs_textospeech", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function textToSpeech(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioTextToSpeechCreateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioTextToSpeechCreateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $output = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['text'];

            $query = [
                AudioTextToSpeechCreateType::FIELD_OPTIMIZE_STREAMING_LATENCY => 0,
                AudioTextToSpeechCreateType::FIELD_OUTPUT_FORMAT => AudioTextToSpeechCreateType::OUTPUT_FORMATS[0]
            ];
            $body = [
                AudioTextToSpeechCreateType::FIELD_TEXT => $data[AudioTextToSpeechCreateType::FIELD_TEXT],
                AudioTextToSpeechCreateType::FIELD_MODEL_ID => $data[AudioTextToSpeechCreateType::FIELD_MODEL_ID],
                AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS => [
                    AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_SIMILIRATY_BOOST => (float)$data[AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_SIMILIRATY_BOOST],
                    AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_STABILITY => (float)$data[AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_STABILITY],
                    AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_STYLE => (float)$data[AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_STYLE],
                    AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_USE_SPEAKER_BOOST => (bool)$data[AudioTextToSpeechCreateType::FIELD_VOICE_SETTINGS_USE_SPEAKER_BOOST]
                ],
            ];

            $response = $this->elevenLabsService->getApiGateway()->getTextToSpeechConnector()->textToSpeech(
                $data[AudioTextToSpeechCreateType::FIELD_VOICE_ID],
                $query,
                $body
            );
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $output = sprintf('data:audio/mp3;base64, %s', base64_encode($content));
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->detail->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/elevenlabs/text_to_speech.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'output' => $output,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
