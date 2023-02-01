<?php

namespace App\Library\Artcustomer\ApiUnit\Http;

class ApiResponse implements IApiResponse {

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $reasonPhrase;

    /**
     * @var string
     */
    private $message;

    /**
     * @var
     */
    private $content;

    /**
     * @var null
     */
    private $customData = null;

    /**
     * ApiResponse constructor.
     * @param int $statusCode
     * @param string $reasonPhrase
     * @param string $message
     * @param null $content
     * @param null $customData
     */
    public function __construct(int $statusCode = 0, string $reasonPhrase = '', string $message = '', $content = null, $customData = null) {
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
        $this->message = $message;
        $this->content = $content;
        $this->customData = $customData;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode): void {
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string {
        return $this->reasonPhrase;
    }

    /**
     * @param mixed $reasonPhrase
     */
    public function setReasonPhrase($reasonPhrase): void {
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void {
        $this->message = $message;
    }

    /**
     * @return object
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void {
        $this->content = $content;
    }

    /**
     * @return null
     */
    public function getCustomData() {
        return $this->customData;
    }

    /**
     * @param $customData
     */
    public function setCustomData($customData): void {
        $this->customData = $customData;
    }

    /**
     * @param $data
     * @return ApiResponse
     */
    public static function __set_state($data): ApiResponse {
        $response = new ApiResponse(
                $data['statusCode'],
                $data['reasonPhrase'],
                $data['message'],
                $data['content'],
                $data['customData']
        );

        return $response;
    }

}
