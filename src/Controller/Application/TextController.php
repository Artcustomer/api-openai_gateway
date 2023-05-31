<?php

namespace App\Controller\Application;

use App\Form\Type\TextCreateCompletionType;
use App\Form\Type\TextPromptType;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/text")
 *
 * @author David
 */
class TextController extends AbstractController {

    protected OpenAIService $openAIService;

    /**
     * Constructor
     */
    public function __construct(OpenAIService $openAIService) {
        $this->openAIService = $openAIService;
    }

    /**
     * @Route("/completion/create", name="application_text_create_completion", methods={"GET","POST"})
     * 
     * @return Response
     */
    public function createCompletion(Request $request): Response {
        $form = $this->createForm(TextCreateCompletionType::class);
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
                'suffix' => null,
                'temperature' => 1,
                'max_tokens' => 16,
                'top_p' => 1,
                'n' => 1,
                'stream' => false
            ];
            $response = $this->openAIService->getApiGateway()->getCompletionConnector()->create($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                $outputResponse = $content->choices[0]->text;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    $content = $response->getContent();

                    if (!empty($content)) {
                        $content = json_decode((string)$content);
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : 'An error occurred';
            }
        }

        return $this->render(
            'application/text/create_completion.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/prompt", name="application_text_prompt", methods={"GET","POST"})
     * 
     * @return Response
     */
    public function prompt(Request $request): Response {
        $form = $this->createForm(TextPromptType::class);
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
                'suffix' => null,
                'temperature' => 1,
                'max_tokens' => 16,
                'top_p' => 1,
                'n' => 1,
                'stream' => false
            ];
            $response = $this->openAIService->getApiGateway()->getCompletionConnector()->create($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                $outputResponse = $content->choices[0]->text;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    $content = $response->getContent();

                    if (!empty($content)) {
                        $content = json_decode((string)$content);
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : 'An error occurred';
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

}
