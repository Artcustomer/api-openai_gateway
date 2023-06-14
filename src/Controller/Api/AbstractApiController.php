<?php

namespace App\Controller\Api;

use App\Service\OpenAIService;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author David
 */
abstract class AbstractApiController extends AbstractController
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
     * Api response proxy
     *
     * @param IApiResponse $response
     * @return JsonResponse
     */
    protected function responseProxy(IApiResponse $response): JsonResponse
    {
        return new JsonResponse($response->getContent(), $response->getStatusCode(), [], true);
    }
}
