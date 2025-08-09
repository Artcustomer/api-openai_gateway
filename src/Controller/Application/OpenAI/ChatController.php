<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\OpenAI\ChatCreateCompletionType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\Model;
use Artcustomer\OpenAIClient\Enum\Role;
use Artcustomer\OpenAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/openai/chat")
 *
 * @author David
 */
class ChatController extends AbstractApplicationController
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
     * @Route("/completion/create", name="application_openai_chat_create_completion", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
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
            $inputModel = $data[ChatCreateCompletionType::FIELD_MODEL];
            $inputPrompt = $data['prompt'];
            $params = [
                'model' => $inputModel,
                'messages' => [
                    [
                        'role' => Role::USER,
                        'content' => $inputPrompt
                    ]
                ],
                'temperature' => $data['temperature'],
                'top_p' => $data['top_p'],
                'n' => $data['n'],
                'max_tokens' => $data['max_tokens'],
                'presence_penalty' => $data['presence_penalty'],
                'frequency_penalty' => $data['frequency_penalty'],
                'user' => $data['user'],
                'stream' => false,
                'logit_bias' => null,
                'logprobs' => false,
                'top_logprobs' => null
            ];

            // Custom behavior depending on the model used
            if (in_array(
                $inputModel,
                [
                    Model::GPT_5_CHAT_LATEST,
                    Model::GPT_5_MINI,
                    Model::GPT_5_NANO
                ]
            )) {
                $params['max_completion_tokens'] = $params['max_tokens'];

                unset($params['max_tokens']);
            }

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
            'application/openai/chat/create_completion.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/converse", name="application_openai_chat_converse", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function converse(Request $request): Response
    {
        return $this->render(
            'application/openai/chat/converse.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
            ]
        );
    }
}
