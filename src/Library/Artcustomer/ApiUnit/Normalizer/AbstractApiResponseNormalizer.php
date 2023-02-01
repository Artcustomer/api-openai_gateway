<?php

namespace App\Library\Artcustomer\ApiUnit\Normalizer;

use App\Library\Artcustomer\ApiUnit\Http\ApiResponse;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;

abstract class AbstractApiResponseNormalizer implements IResponseNormalizer {

    /**
     * @var string
     */
    protected $rule;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * AbstractApiResponseNormalizer constructor.
     */
    public function __construct() {
        
    }

    /**
     * Normalize response data
     * @param ApiResponse $response
     * @return IApiResponse
     */
    abstract public function normalize(ApiResponse &$response): IApiResponse;

    /**
     * @param string $endpoint
     * @return bool
     */
    public function match(string $endpoint): bool {
        return 1 === preg_match($this->pattern, $endpoint);
    }

    /**
     * @return string
     */
    public function getRule(): string {
        return $this->rule;
    }

}
