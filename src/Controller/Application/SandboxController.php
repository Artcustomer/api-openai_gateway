<?php

namespace App\Controller\Application;

use App\Service\OpenAIService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/sandbox")
 *
 * @author David
 */
class SandboxController extends AbstractApplicationController
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
     * @Route("/testing", name="application_sandbox_testing", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function testing(): Response
    {
        return $this->render(
            'application/sandbox/testing.html.twig',
            [

            ]
        );
    }
}
