<?php

namespace App\Library\Artcustomer\ApiUnit\Normalizer;

use App\Library\Artcustomer\ApiUnit\Http\ApiResponse;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;

interface IResponseNormalizer {

    /**
     * @param string $endpoint
     * @return bool
     */
    public function match(string $endpoint): bool;

    /**
     * @param ApiResponse $response
     * @return IApiResponse
     */
    public function normalize(ApiResponse &$response): IApiResponse;
}
