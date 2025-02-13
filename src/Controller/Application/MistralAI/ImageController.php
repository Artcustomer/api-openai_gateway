<?php

namespace App\Controller\Application\MistralAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Mistral\ImageAnalyzeCompletionType;
use App\Service\MistralAIService;
use Artcustomer\MistralAIClient\Enum\Role;
use Artcustomer\MistralAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mistralai/image")
 *
 * @author David
 */
class ImageController extends AbstractApplicationController
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
     * @Route("/analyze", name="application_mistralai_image_analyze", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function analyze(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, ImageAnalyzeCompletionType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(ImageAnalyzeCompletionType::class, null, $options);
        $form->handleRequest($request);

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $execute = true;

            $inputPrompt = $data[ImageAnalyzeCompletionType::FIELD_MESSAGE];
            $inputImageUrl = $data[ImageAnalyzeCompletionType::FIELD_IMAGE_URL];

            /** @var ?UploadedFile $imageFile */
            $imageFile = $data[ImageAnalyzeCompletionType::FIELD_IMAGE];
            $imageUrl = '';

            if (!is_null($imageFile)) {
                $imageUrl = sprintf(
                    'data:%s;base64,%s',
                    $imageFile->getMimeType(),
                    base64_encode($imageFile->getContent())
                );
            } else if (!empty($inputImageUrl)) {
                $imageUrl = $inputImageUrl;
            }

            if (empty($imageUrl)) {
                $errorMessage = $this->trans('error.form.images.not_defined');
                $execute = false;
            }

            if ($execute) {
                $params = [
                    'model' => $data[ImageAnalyzeCompletionType::FIELD_MODEL],
                    'messages' => [
                        [
                            'role' => Role::USER,
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $inputPrompt,
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => $imageUrl,
                                ]
                            ]
                        ]
                    ],
                    'max_tokens' => $data[ImageAnalyzeCompletionType::FIELD_MAX_TOKENS],
                ];
                $response = $this->mistralAIService->getApiGateway()->getChatConnector()->createCompletion($params);
                $content = $response->getContent();

                if ($response->getStatusCode() === 200) {
                    $outputResponse = $content->choices[0]->message->content;
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
        }

        return $this->render(
            'application/mistralai/image/analyze.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }
}