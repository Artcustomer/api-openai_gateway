<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\OpenAI\ImageAnalyzeCompletionType;
use App\Form\Type\OpenAI\ImageCreateType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\Model;
use Artcustomer\OpenAIClient\Enum\OutputFormat;
use Artcustomer\OpenAIClient\Enum\Role;
use Artcustomer\OpenAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $imageUrls = [];
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputModel = $data[ImageCreateType::FIELD_MODEL];
            $inputPrompt = trim($data[ImageCreateType::FIELD_PROMPT]);
            $params = [
                'model' => $inputModel,
                'prompt' => $inputPrompt,
                'n' => $data[ImageCreateType::FIELD_NUMBER],
                'size' => $data[ImageCreateType::FIELD_SIZE],
                'quality' => $data[ImageCreateType::FIELD_QUALITY],
                'style' => $data[ImageCreateType::FIELD_STYLE],
                'background' => $data[ImageCreateType::FIELD_BACKGROUND],
                'moderation' => $data[ImageCreateType::FIELD_MODERATION],
                'output_compression' => $data[ImageCreateType::FIELD_OUTPUT_COMPRESSION],
                'output_format' => $data[ImageCreateType::FIELD_OUTPUT_FORMAT],
                'response_format' => $data[ImageCreateType::FIELD_RESPONSE_FORMAT]
            ];

            if ($inputModel === Model::GPT_IMAGE_1) {
                unset($params['response_format']);
            } else {
                unset($params['background']);
                unset($params['moderation']);
                unset($params['output_compression']);
                unset($params['output_format']);
            }

            if ($inputModel !== Model::DALL_E_3) {
                unset($params['style']);
            }

            $response = $this->openAIService->getApiGateway()->getImageConnector()->create($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $contentData = $content->data;
                $imageFormat = $params['output_format'] ?? OutputFormat::JPEG;

                foreach ($contentData as $item) {
                    $url = '';

                    if (isset($item->url)) {
                        $url = $item->url;
                    }

                    if (isset($item->b64_json)) {
                        $url = sprintf(
                            'data:image/%s;base64, %s',
                            $imageFormat,
                            $item->b64_json
                        );
                    }

                    if (!empty($url)) {
                        $imageUrls[] = $url;
                    }
                }
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
                'imageUrls' => $imageUrls,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }

    /**
     * @Route("/analyze", name="application_openai_image_analyze", methods={"GET","POST"})
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
                                    'image_url' => [
                                        'url' => $imageUrl,
                                        'detail' => $data[ImageAnalyzeCompletionType::FIELD_DETAIL]
                                    ],
                                ]
                            ]
                        ]
                    ],
                    'max_tokens' => $data[ImageAnalyzeCompletionType::FIELD_MAX_TOKENS],
                ];
                $response = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($params);
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
            'application/openai/image/analyze.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
