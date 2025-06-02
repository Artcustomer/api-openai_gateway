<?php

namespace App\Controller\Application\Gemini;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Gemini\TextGenerateType;
use App\Service\GeminiService;
use Artcustomer\GeminiClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gemini/text")
 *
 * @author David
 */
class TextController extends AbstractApplicationController
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
     * @Route("/generate", name="application_gemini_text_generate", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function generate(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, TextGenerateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(TextGenerateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[TextGenerateType::FIELD_PROMPT];
            $inputModel = $data[TextGenerateType::FIELD_MODEL];
            $params = [
                'contents' => [
                    'parts' => [
                        'text' => $inputPrompt
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $data[TextGenerateType::FIELD_TEMPERATURE],
                    'maxOutputTokens' => $data[TextGenerateType::FIELD_MAX_OUTPUT_TOKENS],
                    'topP' => $data[TextGenerateType::FIELD_TOP_P],
                    'topK' => $data[TextGenerateType::FIELD_TOP_K],
                ]
            ];

            $response = $this->geminiService->getApiGateway()->getTextConnector()->generate($inputModel, $params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $outputResponse = $content->candidates[0]->content->parts[0]->text;
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
            'application/gemini/text/generate.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
