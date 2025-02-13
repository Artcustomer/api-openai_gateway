<?php

namespace App\Controller\Application\MistralAI;

use App\Controller\Application\AbstractApplicationController;
use App\Service\MistralAIService;
use Artcustomer\MistralAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mistralai/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
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
     * @Route("/models/list", name="application_mistralai_tools_models_list", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function listModels(Request $request): Response
    {
        $response = $this->mistralAIService->getApiGateway()->getModelConnector()->list();
        $content = $response->getContent();
        $data = [];
        $errorMessage = '';

        if ($response->getStatusCode() === 200) {
            $data = $content->data;
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
            'application/mistralai/tools/list_models.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'data' => $data,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
