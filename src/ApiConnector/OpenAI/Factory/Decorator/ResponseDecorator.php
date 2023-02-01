<?php

namespace App\ApiConnector\OpenAI\Factory\Decorator;

use App\ApiConnector\OpenAI\Utils\ApiTools;
use App\Library\Artcustomer\ApiUnit\Http\ApiResponse;

/**
 * @author David
 */
class ResponseDecorator extends ApiResponse {

    public const CONTENT_ERROR_EMPTY = 1;
    public const CONTENT_ERROR_CONVERT = 2;

    private $responseFormat;
    private $lastContentError = 0;

    /**
     * Constructor
     *
     * @param string $responseFormat
     */
    public function __construct(string $responseFormat = ApiTools::CONTENT_TYPE_JSON) {
        $this->responseFormat = $responseFormat;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void {
        $readContent = null;

        if (!empty($content)) {
            switch ($this->responseFormat) {
                case ApiTools::CONTENT_TYPE_OBJECT:
                    $readContent = $this->toObject($content);
                    break;
                case ApiTools::CONTENT_TYPE_JSON:
                    $readContent = $this->toJson($content);
                    break;
                default:
                    break;
            }

            if ($readContent === null) {
                $readContent = $content;

                $this->lastContentError = self::CONTENT_ERROR_CONVERT;
            }
        } else {
            $this->lastContentError = self::CONTENT_ERROR_EMPTY;
        }

        parent::setContent($readContent);
    }

    /**
     * @param string $responseFormat
     */
    public function setResponseFormat(string $responseFormat): void {
        $this->responseFormat = $responseFormat;
    }

    /**
     * @return string
     */
    public function getResponseFormat(): string {
        return $this->responseFormat;
    }

    /**
     * @return int
     */
    public function getLastContentError(): int {
        return $this->lastContentError;
    }

    /**
     * Directly override parent content
     *
     * @param $content
     */
    public function overrideContent($content): void {
        parent::setContent($content);
    }

    /**
     * Convert response content to object
     *
     * @param $content
     *
     * @return mixed|null
     */
    private function toObject($content) {
        if (gettype($content) === 'object') {
            return $content;
        }

        $jsonDecoded = json_decode($content);

        if (
                $jsonDecoded !== null &&
                $jsonDecoded !== false
        ) {
            return $jsonDecoded;
        }

        return null;
    }

    /**
     * Convert response content to json
     *
     * @param $content
     *
     * @return false|string|null
     */
    private function toJson($content) {
        if (
                gettype($content) === 'string' &&
                json_decode($content) !== null
        ) {
            return $content;
        }

        if (in_array(gettype($content), ['array', 'object'])) {
            return json_encode($content);
        } else {
            if (gettype($content) === 'string') {
                return $content;
            }
        }

        return null;
    }

}
