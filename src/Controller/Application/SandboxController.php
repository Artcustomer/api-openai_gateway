<?php

namespace App\Controller\Application;

use App\Service\EdenAIService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("/sandbox")
 *
 * @author David
 */
class SandboxController extends AbstractApplicationController
{

    protected EdenAIService $edenAIService;


    /**
     * Constructor
     *
     * @param EdenAIService $edenAIService
     */
    public function __construct(EdenAIService $edenAIService)
    {
        $this->edenAIService = $edenAIService;
    }

    /**
     * @Route("/edenai/test", name="application_sandbox_edenai_test", methods={"GET"})
     *
     * @return Response
     */
    public function edenAITest(): Response
    {
        return $this->render(
            'application/sandbox/edenai/test.html.twig',
            [

            ]
        );
    }
}
