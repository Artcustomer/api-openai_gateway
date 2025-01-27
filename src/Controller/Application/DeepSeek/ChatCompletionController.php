<?php

namespace App\Controller\Application\DeepSeek;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\DeepSeek\ChatCompletionCreateType;
use App\Service\DeepSeekService;
use Artcustomer\DeepSeekClient\Enum\Role;
use Artcustomer\DeepSeekClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deepseek/chat-completion")
 *
 * @author David
 */
class ChatCompletionController extends AbstractApplicationController
{

    protected DeepSeekService $deepSeekService;

    /**
     * Constructor
     *
     * @param DeepSeekService $deepSeekService
     */
    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    /**
     * @Route("/create", name="application_deepseek_chat_completion_create", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createChatCompletion(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, ChatCompletionCreateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(ChatCompletionCreateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[ChatCompletionCreateType::FIELD_MESSAGE];
            $inputUser = !empty($data[ChatCompletionCreateType::FIELD_USER])
                ? $data[ChatCompletionCreateType::FIELD_USER]
                : Role::USER;
            $params = [
                'model' => $data[ChatCompletionCreateType::FIELD_MODEL],
                'messages' => [
                    [
                        'role' => $inputUser,
                        'content' => $inputPrompt
                    ]
                ],
            ];
            $response = $this->deepSeekService->getApiGateway()->getChatCompletionsConnector()->create($params);
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
            'application/deepseek/chat_completion/create.html.twig',
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