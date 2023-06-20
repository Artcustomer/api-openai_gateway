<?php

namespace App\Controller\Application;

use App\Service\OpenAIService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/usage")
 *
 * @author David
 */
class UsageController extends AbstractApplicationController
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
     * @Route("/", name="application_usage_index", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        $dateTime = \DateTime::createFromFormat('d/m/Y', '01/01/2022');
        $response = $this->openAIService->getApiGateway()->getUsageConnector()->get($dateTime);

        return $this->render(
            'application/usage/index.html.twig',
            []
        );
    }
}
