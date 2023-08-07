<?php

namespace App\Controller\Application;

use App\Form\Type\AbstractExtendedType;
use App\Form\Type\UserApiSettingsType;
use App\Service\OpenAIService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 *
 * @author David
 */
class UserController extends AbstractApplicationController
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
     * @Route("/", name="application_user_index", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'application/user/index.html.twig',
            []
        );
    }

    /**
     * @Route("/usage", name="application_user_usage", methods={"GET"})
     *
     * @return Response
     * @throws \ReflectionException
     */
    public function usage(): Response
    {
        $dateTime = \DateTime::createFromFormat('d/m/Y', '01/01/2022');
        $response = $this->openAIService->getApiGateway()->getUsageConnector()->get($dateTime);

        return $this->render(
            'application/user/usage.html.twig',
            []
        );
    }

    /**
     * @Route("/api-settings", name="application_user_apisettings", methods={"GET","POST"})
     *
     * @param Request $request
     * @param OpenAIService $openAIService
     * @return Response
     */
    public function apiSettings(Request $request, OpenAIService $openAIService): Response
    {
        $formData = $this->cleanQueryParameters($request, UserApiSettingsType::FIELD_NAMES);
        $isApiKeyInEnv = $openAIService->isApiKeyInEnv();
        $options = ['data' => $formData];
        $options[AbstractExtendedType::OPT_CUSTOM_FIELD_OPTIONS] = [
            UserApiSettingsType::FIELD_API_TOKEN => [
                'disabled' => $isApiKeyInEnv
            ]
        ];

        $form = $this->createForm(UserApiSettingsType::class, null, $options);

        $form->handleRequest($request);

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $apiToken = $data[UserApiSettingsType::FIELD_API_TOKEN];

            $openAIService->setApiKeyInSession($apiToken);
        }

        return $this->render(
            'application/user/api_settings.html.twig',
            [
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
