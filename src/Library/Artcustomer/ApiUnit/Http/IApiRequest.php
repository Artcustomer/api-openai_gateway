<?php

namespace App\Library\Artcustomer\ApiUnit\Http;

interface IApiRequest extends IHttpItem {

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * @return array
     */
    public function getQuery(): ?array;

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return bool
     */
    public function isAsync(): bool;

    /**
     * @return bool
     */
    public function isSecured(): bool;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return array
     */
    public function getHeadersParams(): array;

    /**
     * @return mixed
     */
    public function getCustomData();
}
