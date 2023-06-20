<?php

namespace App\Controller\Application;

use App\Service\OpenAIService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
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
     * @Route("/prompts-samples", name="application_tools_promptssamples", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'application/tools/prompts_samples.html.twig',
            []
        );
    }
}
