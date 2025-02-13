<?php

namespace App\Controller\Application\DeepSeek;

use App\Controller\Application\AbstractApplicationController;
use App\Service\DeepSeekService;
use Artcustomer\DeepSeekClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deepseek/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
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
     * @Route("/models/list", name="application_deepseek_tools_models_list", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function listModels(Request $request): Response
    {
        $response = $this->deepSeekService->getApiGateway()->getModelConnector()->list();
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
            'application/deepseek/tools/list_models.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'data' => $data,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
