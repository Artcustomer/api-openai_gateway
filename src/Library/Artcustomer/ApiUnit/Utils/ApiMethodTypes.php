<?php

namespace App\Library\Artcustomer\ApiUnit\Utils;

class ApiMethodTypes {

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
    const COPY = 'COPY';
    const HEAD = 'HEAD';
    const OPTIONS = 'OPTIONS';
    const LINK = 'LINK';
    const UNLINK = 'UNLINK';
    const PURGE = 'PURGE';
    const LOCK = 'LOCK';
    const UNLOCK = 'UNLOCK';
    const PROPFIND = 'PROPFIND';
    const VIEW = 'VIEW';
    const METHOD_LIST = [
        self::GET,
        self::POST,
        self::PUT,
        self::PATCH,
        self::DELETE,
        self::COPY,
        self::HEAD,
        self::OPTIONS,
        self::LINK,
        self::UNLINK,
        self::PURGE,
        self::LOCK,
        self::UNLOCK,
        self::PROPFIND,
        self::VIEW,
    ];

    /**
     * Test if method exists
     * @param string|NULL $method
     * @return bool
     */
    public static function hasMethod(string $method = NULL): bool {
        if (NULL !== $method) {
            return in_array($method, self::METHOD_LIST, true);
        }

        return FALSE;
    }

}
