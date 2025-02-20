<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
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
     */
    public function audioCompletion(Request $request): Response
    {
        $errorMessage = '';

        return $this->render(
            'application/openai/feature/audio_completion.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
