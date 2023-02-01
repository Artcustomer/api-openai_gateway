<?php

namespace App\Library\Artcustomer\ApiUnit\Event;

use App\Library\Artcustomer\ApiUnit\Http\IApiRequest;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;

interface IApiEvent {

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return IApiRequest
     */
    public function getRequest(): IApiRequest;

    /**
     * @return IApiResponse
     */
    public function getResponse(): IApiResponse;
}
