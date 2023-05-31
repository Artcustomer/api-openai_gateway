<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Artcustomer\ApiUnit\Http\IApiResponse;
use App\Service\OpenAIService;

/**
 * @author David
 */
abstract class AbstractApiController extends AbstractController {

    protected OpenAIService $openAIService;

    /**
     * Constructor
     */
    public function __construct(OpenAIService $openAIService) {
        $this->openAIService = $openAIService;
    }

    /**
     * Api response proxy
     */
    protected function responseProxy(IApiResponse $response): JsonResponse {
        return new JsonResponse($response->getContent(), $response->getStatusCode(), [], true);
    }

}
