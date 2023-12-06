<?php

namespace App\Controller\Application;

use App\Form\Type\TextPromptType;
use App\Form\Type\TextTranslateType;
use App\Service\OpenAIService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/prompt", name="application_text_prompt", methods={"GET","POST"})
     * @IsGranted("ROLE_APP")
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function prompt(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, TextPromptType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(TextPromptType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['prompt'];
            $inputModel = $data['model'];

            $params = [
                'model' => $inputModel,
                'prompt' => $inputPrompt,
                'suffix' => $data['suffix'],
                'max_tokens' => $data['max_tokens'],
                'temperature' => $data['temperature'],
                'top_p' => $data['top_p'],
                'n' => $data['n'],
                'echo' => $data['echo'],
                'presence_penalty' => $data['presence_penalty'],
                'frequency_penalty' => $data['frequency_penalty'],
                'best_of' => $data['best_of'],
                'user' => $data['user'],
                'stream' => false
            ];
            $response = $this->openAIService->getApiGateway()->getCompletionConnector()->create($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $outputResponse = $content->choices[0]->text;
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
            'application/text/prompt.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
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
                'model' => 'text-davinci-003',
                'prompt' => $fullPrompt,
                'suffix' => null,
                'temperature' => 0.3,
                'max_tokens' => 1024,
                'top_p' => 1,
                'n' => 1,
                'stream' => false,
                'frequency_penalty' => 0,
                'presence_penalty' => 0
            ];
            $response = $this->openAIService->getApiGateway()->getCompletionConnector()->create($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $outputResponse = $content->choices[0]->text;
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
}
