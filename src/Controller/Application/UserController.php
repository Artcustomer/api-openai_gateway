<?php

namespace App\Controller\Application;

use App\Form\Type\AbstractExtendedType;
use App\Form\Type\UserApiSettingsType;
use App\Service\EdenAIService;
use App\Service\ElevenLabsService;
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

    /**
     * @var OpenAIService
     */
    protected OpenAIService $openAIService;

    /**
     * @var EdenAIService
     */
    protected EdenAIService $edenAIService;

    /**
     * @var ElevenLabsService
     */
    protected ElevenLabsService $elevenLabsService;

    /**
     * Constructor
     *
     * @param OpenAIService $openAIService
     * @param EdenAIService $edenAIService
     * @param ElevenLabsService $elevenLabsService
     */
    public function __construct(OpenAIService $openAIService, EdenAIService $edenAIService, ElevenLabsService $elevenLabsService)
    {
        $this->openAIService = $openAIService;
        $this->edenAIService = $edenAIService;
        $this->elevenLabsService = $elevenLabsService;
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
     * @return Response
     */
    public function apiSettings(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, UserApiSettingsType::FIELD_NAMES);
        $options = ['data' => $formData];
        $options[AbstractExtendedType::OPT_CUSTOM_FIELD_OPTIONS] = [
            UserApiSettingsType::FIELD_OPENAI_API_TOKEN => [
                'disabled' => $this->openAIService->isApiKeyInEnv()
            ],
            UserApiSettingsType::FIELD_EDENAI_API_TOKEN => [
                'disabled' => $this->edenAIService->isApiKeyAvailable()
            ],
            UserApiSettingsType::FIELD_ELEVENLABS_API_TOKEN => [
                'disabled' => $this->elevenLabsService->isApiKeyAvailable()
            ]
        ];

        $form = $this->createForm(UserApiSettingsType::class, null, $options);

        $form->handleRequest($request);

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $openAiApiToken = $data[UserApiSettingsType::FIELD_OPENAI_API_TOKEN];
            $edenAiApiToken = $data[UserApiSettingsType::FIELD_EDENAI_API_TOKEN];
            $elevenLabsApiToken = $data[UserApiSettingsType::FIELD_ELEVENLABS_API_TOKEN];

            $this->openAIService->setApiKeyInSession($openAiApiToken);
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
