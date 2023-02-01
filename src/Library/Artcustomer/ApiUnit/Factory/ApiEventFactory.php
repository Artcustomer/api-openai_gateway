<?php

namespace App\Library\Artcustomer\ApiUnit\Factory;

use App\Library\Artcustomer\ApiUnit\Event\ApiExternalEvent;
use App\Library\Artcustomer\ApiUnit\Event\IApiEvent;
use App\Library\Artcustomer\ApiUnit\Http\IApiRequest;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;

class ApiEventFactory {

    const TYPE_INTERNAL = 'internal';
    const TYPE_EXTERNAL = 'external';

    /**
     * ApiEventFactory constructor.
     */
    public function __construct() {
        
    }

    /**
     * Create event
     * @param string $type
     * @param string $eventName
     * @param IApiRequest|null $request
     * @param IApiResponse|null $response
     * @return null|IApiEvent
     */
    public function create(string $type, string $eventName, IApiRequest $request = null, IApiResponse $response = null): ?IApiEvent {
        $event = null;

        switch ($type) {
            case self::TYPE_INTERNAL:
                break;

            case self::TYPE_EXTERNAL:
                $event = new ApiExternalEvent();
                break;

            default:
                break;
        }

        if (NULL !== $event) {
            $event->setName($eventName);
            $event->setRequest($request);
            $event->setResponse($response);
        }

        return $event;
    }

}
