<?php

namespace App\Event;

use Artcustomer\ApiUnit\Http\IApiRequest;
use Artcustomer\ApiUnit\Http\IApiResponse;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author David
 */
class ApiCallEvent extends Event
{

    public const EVENT_API_CALL_POST_EXECUTE = 'api.call.post_execute';

    protected ?IApiRequest $request;
    protected ?IApiResponse $response;

    /**
     * @param IApiRequest|null $request
     * @param IApiResponse|null $response
     */
    public function __construct(IApiRequest $request = null, IApiResponse $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return IApiRequest|null
     */
    public function getRequest(): ?IApiRequest
    {
        return $this->request;
    }

    /**
     * @return IApiResponse|null
     */
    public function getResponse(): ?IApiResponse
    {
        return $this->response;
    }
}
