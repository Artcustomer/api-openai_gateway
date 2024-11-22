<?php

namespace App\Controller\Application;

use App\Service\MistralAIService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/toolbox/sandbox")
 *
 * @author David
 */
class SandboxController extends AbstractApplicationController
{

    protected MistralAIService $mistralAIService;

    /**
     * Constructor
     *
     * @param MistralAIService $mistralAIService
     */
    public function __construct(MistralAIService $mistralAIService)
    {
        $this->mistralAIService = $mistralAIService;
    }

    /**
     * @Route("/testing", name="application_toolbox_sandbox_testing", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function testing(): Response
    {
        $gateway = $this->mistralAIService->getApiGateway();

        return $this->render(
            'application/sandbox/testing.html.twig',
            [

            ]
        );
    }
}
