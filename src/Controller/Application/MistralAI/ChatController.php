<?php

namespace App\Controller\Application\MistralAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Mistral\ChatCreateCompletionType;
use App\Service\MistralAIService;
use Artcustomer\MistralAIClient\Enum\Role;
use Artcustomer\MistralAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mistralai/chat")
 *
 * @author David
 */
class ChatController extends AbstractApplicationController
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
     * @Route("/completion/create", name="application_mistralai_chat_create_completion", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createCompletion(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, ChatCreateCompletionType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(ChatCreateCompletionType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[ChatCreateCompletionType::FIELD_MESSAGE];
            $params = [
                'model' => $data[ChatCreateCompletionType::FIELD_MODEL],
                'messages' => [
                    [
                        'role' => Role::USER,
                        'content' => $inputPrompt
                    ]
                ],
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

        return $this->render(
            'application/mistralai/chat/create_completion.html.twig',
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