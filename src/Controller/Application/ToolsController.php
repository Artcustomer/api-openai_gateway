<?php

namespace App\Controller\Application;

use App\Service\OpenAIService;
use App\Service\PromptService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
{

    protected OpenAIService $openAIService;
    
    protected PromptService $promptService;

    /**
     * Constructor
     *
     * @param OpenAIService $openAIService
     * @param PromptService $promptService
     */
    public function __construct(OpenAIService $openAIService, PromptService $promptService)
    {
        $this->openAIService = $openAIService;
        $this->promptService = $promptService;
    }

    /**
     * @Route("/prompts-samples", name="application_tools_promptssamples", methods={"GET"})
     *
     * @return Response
     */
    public function promptSamples(): Response
    {
        $samples = $this->promptService->getSamples();
        $numColumns = 2;
        $chunks = array_chunk($samples, ceil(count($samples) / $numColumns), true);

        return $this->render(
            'application/tools/prompts_samples.html.twig',
            [
                'chunks' => $chunks,
                'numColumns' => $numColumns
            ]
        );
    }
}
