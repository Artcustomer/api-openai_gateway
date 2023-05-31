<?php

namespace App\Controller\Application;

use App\Form\Type\ChatCreateCompletionType;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/chat")
 *
 * @author David
 */
class ChatController extends AbstractController {

    protected OpenAIService $openAIService;

    /**
     * Constructor
     */
    public function __construct(OpenAIService $openAIService) {
        $this->openAIService = $openAIService;
    }

    /**
     * @Route("/completion/create", name="application_chat_create_completion", methods={"GET","POST"})
     * 
     * @return Response
     */
    public function createCompletion(Request $request): Response {
        $form = $this->createForm(ChatCreateCompletionType::class);
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
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $inputPrompt
                    ]
                ],
                'temperature' => 1,
                'max_tokens' => 250,
                'top_p' => 1,
                'n' => 1,
                'stream' => false
            ];
            $response = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                $outputResponse = $content->choices[0]->message->content;
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
            'application/chat/create_completion.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/converse", name="application_chat_converse", methods={"GET"})
     * 
     * @return Response
     */
    public function converse(Request $request): Response {


        return $this->render(
            'application/chat/converse.html.twig',
            [
                
            ]
        );
    }

}
