<?php

namespace App\Controller\Application;

use App\Service\ElevenLabsService;
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
