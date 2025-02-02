<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\OpenAI\ImageCreateType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\ResponseFormat;
use Artcustomer\OpenAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/openai/image")
 *
 * @author David
 */
class ImageController extends AbstractApplicationController
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
     * @Route("/create", name="application_openai_image_create", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function create(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, ImageCreateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(ImageCreateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $imageUrl = '';
        $imageUrls = [];
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[ImageCreateType::FIELD_PROMPT];
            $inputNumber = $data[ImageCreateType::FIELD_NUMBER];
            $params = [
                'model' => $data[ImageCreateType::FIELD_MODEL],
                'prompt' => $inputPrompt,
                'n' => $inputNumber,
                'size' => $data[ImageCreateType::FIELD_SIZE],
                'response_format' => ResponseFormat::URL
            ];
            $response = $this->openAIService->getApiGateway()->getImageConnector()->create($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $contentData = $content->data;
                $imageUrls = array_map(
                    function ($item) {
                        return $item->url;
                    },
                    $contentData
                );
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/openai/image/create.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'imageUrl' => $imageUrl,
                'imageUrls' => $imageUrls,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }
}
