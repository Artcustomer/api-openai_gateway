<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\OpenAI\FeatureAudioCompletionType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/openai/feature")
 *
 * @author David
 */
class FeatureController extends AbstractApplicationController
{

    protected OpenAIService $openAIService;

    /**
     * Constructor
     *
     * @param OpenAIService $openAIService
     */
    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * @Route("/audio-completion", name="application_openai_feature_audio_completion", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function audioCompletion(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, FeatureAudioCompletionType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(FeatureAudioCompletionType::class, null, $options);
        $form->handleRequest($request);

        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render(
            'application/openai/feature/audio_completion.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
