<?php

namespace App\EventListener;

use App\Event\ApiCallEvent;
use App\Service\CacheService;
use App\Utils\Consts\CacheConsts;
use Artcustomer\ApiUnit\Http\IApiRequest;
use Artcustomer\ApiUnit\Http\IApiResponse;

/**
 * @author David
 */
class ApiCallListener
{

    protected CacheService $cache;

    /**
     * @param CacheService $cache
     */
    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param ApiCallEvent $event
     * @return void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function onApiCallPostExecuteHandler(ApiCallEvent $event): void
    {
        $cacheItem = $this->cache->getItem(CacheConsts::DEBUG_API_CALLS);

        if ($cacheItem !== null) {
            $value = $cacheItem->get() ?? [];
            $value[] = $this->marshallCallForCache($event->getRequest(), $event->getResponse());

            $this->cache->set(CacheConsts::DEBUG_API_CALLS, $value);
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
