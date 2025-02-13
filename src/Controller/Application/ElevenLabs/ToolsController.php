<?php

namespace App\Controller\Application\ElevenLabs;

use App\Controller\Application\AbstractApplicationController;
use App\Service\ElevenLabsService;
use Artcustomer\ElevenLabsClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/elevenlabs/tools")
 *
 * @author David
 */
class ToolsController extends AbstractApplicationController
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
     * @Route("/models/list", name="application_elevenlabs_tools_models_list", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function listModels(Request $request): Response
    {
        $response = $this->elevenLabsService->getApiGateway()->getModelsConnector()->getModels();
        $content = $response->getContent();
        $data = [];
        $errorMessage = '';

        if ($response->getStatusCode() === 200) {
            $data = $content;
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
            'application/elevenlabs/tools/list_models.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'data' => $data,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
