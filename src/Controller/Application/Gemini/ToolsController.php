<?php

namespace App\Controller\Application\Gemini;

use App\Controller\Application\AbstractApplicationController;
use App\Service\GeminiService;
use Artcustomer\GeminiClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gemini/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
{

    protected GeminiService $geminiService;

    /**
     * Constructor
     *
     * @param GeminiService $geminiService
     */
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * @Route("/models/list", name="application_gemini_tools_models_list", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function listModels(Request $request): Response
    {
        $response = $this->geminiService->getApiGateway()->getModelConnector()->list();
        $content = $response->getContent();
        $data = [];
        $errorMessage = '';

        if ($response->getStatusCode() === 200) {
            $data = $content->models;
        } else {
            $errorMessage = $response->getMessage();

            if (empty($errorMessage)) {
                if (!empty($content)) {
                    $errorMessage = $content->error->message ?? '';
                }
            }

            $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
        }

        return $this->render(
            'application/gemini/tools/list_models.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'data' => $data,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
