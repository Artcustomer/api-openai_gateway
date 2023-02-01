<?php

namespace App\Library\Artcustomer\ApiUnit\Event;

use App\Library\Artcustomer\ApiUnit\Http\IApiRequest;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;

abstract class AbstractApiEvent {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var IApiRequest
     */
    protected $request;

    /**
     * @var IApiResponse
     */
    protected $response;

    /**
     * AbstractApiEvent constructor.
     */
    public function __construct() {
        
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return IApiRequest
     */
    public function getRequest(): IApiRequest {
        return $this->request;
    }

    /**
     * @param null|IApiRequest $request
     */
    public function setRequest(?IApiRequest $request): void {
        $this->request = $request;
    }

    /**
     * @return IApiResponse
     */
    public function getResponse(): IApiResponse {
        return $this->response;
    }

    /**
     * @param null|IApiResponse $response
     */
    public function setResponse(?IApiResponse $response): void {
        $this->response = $response;
    }

}
