<?php

namespace App\Controller\Application;

use App\Service\XAIService;
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

    protected XAIService $xAIService;

    /**
     * Constructor
     *
     * @param XAIService $xAIService
     */
    public function __construct(XAIService $xAIService)
    {
        $this->xAIService = $xAIService;
    }

    /**
     * @Route("/testing", name="application_toolbox_sandbox_testing", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function testing(): Response
    {
        $gateway = $this->xAIService->getApiGateway();
        $connector = $gateway->getChatCompletionsConnector();

        $params = [
            'model' => 'grok-beta',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'Can you generate an image from a prompt ?',
                ],
            ]
        ];
        //$response = $connector->create($params);

        return $this->render(
            'application/sandbox/testing.html.twig',
            [

            ]
        );
    }
}
