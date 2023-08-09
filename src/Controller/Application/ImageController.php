<?php

namespace App\Controller\Application;

use App\Form\Type\ImageCreateType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\ResponseFormat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/image")
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
     * @Route("/create", name="application_image_create", methods={"GET","POST"})
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
            $inputPrompt = $data['prompt'];
            $inputNumber = $data['number'];
            $params = [
                'prompt' => $inputPrompt,
                'n' => $inputNumber,
                'size' => $data['size'],
                'response_format' => ResponseFormat::URL
            ];
            $response = $this->openAIService->getApiGateway()->getImageConnector()->create($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());

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
                    $content = $response->getContent();

                    if (!empty($content)) {
                        $content = json_decode((string)$content);
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/image/create.html.twig',
            [
                'form' => $form,
                'imageUrl' => $imageUrl,
                'imageUrls' => $imageUrls,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }
}
