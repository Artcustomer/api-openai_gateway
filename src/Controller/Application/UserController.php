<?php

namespace App\Controller\Application;

use App\Form\Type\AbstractExtendedType;
use App\Form\Type\UserApiSettingsType;
use App\Service\DeepSeekService;
use App\Service\EdenAIService;
use App\Service\ElevenLabsService;
use App\Service\MistralAIService;
use App\Service\OpenAIService;
use App\Service\XAIService;
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

    private const PROVIDER_OPENAI = 'openai';
    private const PROVIDER_EDENAI = 'edenai';
    private const PROVIDER_ELEVENLABS = 'elevenlabs';
    private const PROVIDER_MISTRALAI = 'mistralai';
    private const PROVIDER_XAI = 'xai';
    private const PROVIDER_DEEPSEEK = 'deepseek';

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
     * @var MistralAIService
     */
    protected MistralAIService $mistralAIService;

    /**
     * @var XAIService
     */
    protected XAIService $xAIService;

    /**
     * @var DeepSeekService
     */
    protected DeepSeekService $deepSeekService;

    /**
     * Constructor
     *
     * @param OpenAIService $openAIService
     * @param EdenAIService $edenAIService
     * @param ElevenLabsService $elevenLabsService
     * @param MistralAIService $mistralAIService
     * @param XAIService $xAIService
     * @param DeepSeekService $deepSeekService
     */
    public function __construct(
        OpenAIService     $openAIService,
        EdenAIService     $edenAIService,
        ElevenLabsService $elevenLabsService,
        MistralAIService  $mistralAIService,
        XAIService        $xAIService,
        DeepSeekService   $deepSeekService
    )
    {
        $this->openAIService = $openAIService;
        $this->edenAIService = $edenAIService;
        $this->elevenLabsService = $elevenLabsService;
        $this->mistralAIService = $mistralAIService;
        $this->xAIService = $xAIService;
        $this->deepSeekService = $deepSeekService;
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
        $enabled = false;

        if ($enabled) {
            $response = $this->openAIService->getApiGateway()->getUsageConnector()->getCosts($dateTime);
        }

        return $this->render(
            'application/user/usage.html.twig',
            []
        );
    }

    /**
     * @Route("/status", name="application_user_status", methods={"GET"})
     *
     * @return Response
     * @throws \ReflectionException
     */
    public function status(): Response
    {
        $data = [
            self::PROVIDER_OPENAI => ['name' => \Artcustomer\OpenAIClient\Utils\ApiInfos::API_NAME, 'status' => false],
            self::PROVIDER_EDENAI => ['name' => \Artcustomer\EdenAIClient\Utils\ApiInfos::API_NAME, 'status' => false],
            self::PROVIDER_ELEVENLABS => ['name' => \Artcustomer\ElevenLabsClient\Utils\ApiInfos::API_NAME, 'status' => false],
            self::PROVIDER_MISTRALAI => ['name' => \Artcustomer\MistralAIClient\Utils\ApiInfos::API_NAME, 'status' => false],
            self::PROVIDER_XAI => ['name' => \Artcustomer\XAIClient\Utils\ApiInfos::API_NAME, 'status' => false],
            self::PROVIDER_DEEPSEEK => ['name' => \Artcustomer\DeepSeekClient\Utils\ApiInfos::API_NAME, 'status' => false]
        ];

        if ($this->openAIService->getApiGateway()->test()->getStatusCode() === Response::HTTP_OK) {
            $data[self::PROVIDER_OPENAI]['status'] = true;
        }

        if ($this->edenAIService->getApiGateway()->test()->getStatusCode() === Response::HTTP_OK) {
            $data[self::PROVIDER_EDENAI]['status'] = true;
        }

        if ($this->elevenLabsService->getApiGateway()->test()->getStatusCode() === Response::HTTP_OK) {
            $data[self::PROVIDER_ELEVENLABS]['status'] = true;
        }

        if ($this->mistralAIService->getApiGateway()->test()->getStatusCode() === Response::HTTP_OK) {
            $data[self::PROVIDER_MISTRALAI]['status'] = true;
        }

        if ($this->xAIService->getApiGateway()->test()->getStatusCode() === Response::HTTP_OK) {
            $data[self::PROVIDER_XAI]['status'] = true;
        }

        if ($this->deepSeekService->getApiGateway()->test()->getStatusCode() === Response::HTTP_OK) {
            $data[self::PROVIDER_DEEPSEEK]['status'] = true;
        }

        return $this->render(
            'application/user/status.html.twig',
            [
                'data' => $data
            ]
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
                'disabled' => $this->openAIService->isApiKeyAvailable()
            ],
            UserApiSettingsType::FIELD_EDENAI_API_TOKEN => [
                'disabled' => $this->edenAIService->isApiKeyAvailable()
            ],
            UserApiSettingsType::FIELD_ELEVENLABS_API_TOKEN => [
                'disabled' => $this->elevenLabsService->isApiKeyAvailable()
            ],
            UserApiSettingsType::FIELD_MISTRALAI_API_TOKEN => [
                'disabled' => $this->mistralAIService->isApiKeyAvailable()
            ],
            UserApiSettingsType::FIELD_XAI_API_TOKEN => [
                'disabled' => $this->xAIService->isApiKeyAvailable()
            ],
            UserApiSettingsType::FIELD_DEEPSEEK_API_TOKEN => [
                'disabled' => $this->deepSeekService->isApiKeyAvailable()
            ]
        ];

        $form = $this->createForm(UserApiSettingsType::class, null, $options);

        $form->handleRequest($request);

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $openAIApiToken = $data[UserApiSettingsType::FIELD_OPENAI_API_TOKEN];
            $edenAIApiToken = $data[UserApiSettingsType::FIELD_EDENAI_API_TOKEN];
            $elevenLabsApiToken = $data[UserApiSettingsType::FIELD_ELEVENLABS_API_TOKEN];
            $mistralAIApiToken = $data[UserApiSettingsType::FIELD_MISTRALAI_API_TOKEN];
            $xAIApiToken = $data[UserApiSettingsType::FIELD_XAI_API_TOKEN];
            $deepSeekApiToken = $data[UserApiSettingsType::FIELD_DEEPSEEK_API_TOKEN];

            $this->openAIService->setApiKeyInSession($openAIApiToken);
            $this->edenAIService->setApiKeyInSession($edenAIApiToken);
            $this->elevenLabsService->setApiKeyInSession($elevenLabsApiToken);
            $this->mistralAIService->setApiKeyInSession($mistralAIApiToken);
            $this->xAIService->setApiKeyInSession($xAIApiToken);
            $this->deepSeekService->setApiKeyInSession($deepSeekApiToken);
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
