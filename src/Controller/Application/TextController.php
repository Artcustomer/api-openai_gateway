<?php

namespace App\Controller\Application;

use App\Form\Type\TextCorrectType;
use App\Form\Type\TextTranslateType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/text")
 *
 * @author David
 */
class TextController extends AbstractApplicationController
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
     * @Route("/translate", name="application_text_translate", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function translate(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, TextTranslateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(TextTranslateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['prompt'];
            $fromLanguage = $data['from_language'];
            $toLanguage = $data['to_language'];
            $fullPrompt = sprintf('Translate this from %s into %s: %s', $fromLanguage, $toLanguage, $inputPrompt);
            $params = [
                'model' => Model::GPT_4,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $fullPrompt
                    ]
                ],
                'temperature' => 1,
                'top_p' => 1,
                'n' => 1,
                'max_tokens' => 2056,
                'presence_penalty' => 0,
                'frequency_penalty' => 0,
                'user' => '',
                'stream' => false,
                'logit_bias' => null,
                'logprobs' => false,
                'top_logprobs' => null
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

        return $this->render(
            'application/text/translate.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/correct", name="application_text_correct", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function correct(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, TextCorrectType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(TextCorrectType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = [];
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['prompt'];
            $language = $data['language'];
            $basePrompt = sprintf(
                'Correct the mistakes and rewrite this text written in %s: "%s".',
                $language,
                $inputPrompt
            );
            $fullPrompt = $basePrompt . '\n\n' .
                'Extract the corrected words and create a JSON object with the result and the corrections.\n\n' .
                'Assign the result to the property "output" and the corrections to the property "corrections".\n\n' .
                'For each correction assign to the property "a" the wrong word and to the property "b" the right word' .
                'The resulting JSON object should be in this format:' .
                '{"output":"string","corrections":[{"a":"string","b":"string"}]}';
            $params = [
                'model' => Model::GPT_4_TURBO,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $fullPrompt
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 1,
                'top_p' => 1,
                'n' => 1,
                'max_tokens' => 2056,
                'presence_penalty' => 0,
                'frequency_penalty' => 0,
                'user' => '',
                'stream' => false,
                'logit_bias' => null,
                'logprobs' => false,
                'top_logprobs' => null
            ];
            $response = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $messageContent = $content->choices[0]->message->content;
                $jsonResponse = json_decode($messageContent, true);

                if (!is_null($jsonResponse)) {
                    if (
                        isset($jsonResponse['output']) &&
                        isset($jsonResponse['corrections'])
                    ) {
                        $outputResponse = $jsonResponse;
                    } else {
                        $errorMessage = $this->trans('error.format.json.invalid_structure');
                    }
                } else {
                    $errorMessage = $this->trans('error.format.json.invalid');
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
            'application/text/correct.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
