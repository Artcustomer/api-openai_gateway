<?php

namespace App\Library\Artcustomer\ApiUnit\Factory;

use App\Library\Artcustomer\ApiUnit\Http\ApiResponse;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;

class ApiResponseFactory {

    /**
     * @var ApiResponse
     */
    private $responseDecorator;

    /**
     * ApiResponseFactory constructor.
     * @param ApiResponse|NULL $responseDecorator
     */
    public function __construct(ApiResponse $responseDecorator = NULL) {
        $this->responseDecorator = $responseDecorator;
    }

    /**
     * Create ApiResponse instance
     * @param int $statusCode
     * @param string $reasonPhrase
     * @param string $message
     * @param null $content
     * @param null $customData
     * @return IApiResponse
     */
    public function create(int $statusCode, string $reasonPhrase = '', string $message = '', $content = null, $customData = null): IApiResponse {
        $response = new ApiResponse();

        if (NULL !== $this->responseDecorator) {
            $response = $this->responseDecorator;
        }

        $response->setStatusCode($statusCode);
        $response->setReasonPhrase($reasonPhrase);
        $response->setMessage($message);
        $response->setContent($content);
        $response->setCustomData($customData);

        return $response;
    }

}
