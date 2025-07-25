<?php

namespace App\EventListener;

use App\Event\ApiCallEvent;
use App\Service\CacheService;
use App\Service\FlashMessageService;
use App\Utils\Consts\CacheConsts;
use Artcustomer\ApiUnit\Http\IApiRequest;
use Artcustomer\ApiUnit\Http\IApiResponse;

/**
 * @author David
 */
class ApiCallListener
{

    protected CacheService $cache;
    protected FlashMessageService $flashMessageService;

    /**
     * @param CacheService $cache
     * @param FlashMessageService $flashMessageService
     */
    public function __construct(CacheService $cache, FlashMessageService $flashMessageService)
    {
        $this->cache = $cache;
        $this->flashMessageService = $flashMessageService;
    }

    /**
     * @param ApiCallEvent $event
     * @return void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function onApiCallPostExecuteHandler(ApiCallEvent $event): void
    {
        $eventRequest = $event->getRequest();
        $eventResponse = $event->getResponse();

        if ($eventResponse->getStatusCode() !== 200) {
            $this->flashMessageService->addFlash(
                FlashMessageService::TYPE_ERROR,
                sprintf('%s: %s', $eventResponse->getStatusCode(), $eventRequest->getUri())
            );
        }

        if ($this->cache->isCacheAvailable()) {
            $cacheItem = $this->cache->getItem(CacheConsts::DEBUG_API_CALLS);

            if ($cacheItem !== null) {
                $value = $cacheItem->get() ?? [];
                $value[] = $this->marshallCallForCache($eventRequest, $eventResponse);

                $this->cache->set(CacheConsts::DEBUG_API_CALLS, $value);
            }
        }
    }

    /**
     * @param IApiRequest $request
     * @param IApiResponse $response
     * @return array
     */
    private function marshallCallForCache(IApiRequest $request, IApiResponse $response): array
    {
        $content = $response->getContent();

        return [
            'url' => $request->getUri(),
            'method' => $request->getMethod(),
            'code' => $response->getStatusCode(),
            'message' => $response->getMessage(),
            'reasonPhrase' => $response->getMessage(),
            'content' => is_string($content) ? $content : json_encode($content)
        ];
    }
}
