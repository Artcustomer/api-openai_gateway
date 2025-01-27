<?php

namespace App\Controller\Application;

use App\Service\DeepSeekService;
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

    protected DeepSeekService $deepSeekService;

    /**
     * Constructor
     *
     * @param DeepSeekService $deepSeekService
     */
    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    /**
     * @Route("/testing", name="application_toolbox_sandbox_testing", methods={"GET", "POST"})
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
