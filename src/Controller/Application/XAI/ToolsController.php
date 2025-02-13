<?php

namespace App\Controller\Application\XAI;

use App\Controller\Application\AbstractApplicationController;
use App\Service\XAIService;
use Artcustomer\XAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/xai/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
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
     * @Route("/models/list", name="application_xai_tools_models_list", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function listModels(Request $request): Response
    {
        $response = $this->xAIService->getApiGateway()->getModelConnector()->list();
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
            'application/xai/tools/list_models.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'data' => $data,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
