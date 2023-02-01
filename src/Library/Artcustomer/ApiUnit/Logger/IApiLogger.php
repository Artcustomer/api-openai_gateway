<?php

namespace App\Library\Artcustomer\ApiUnit\Logger;

interface IApiLogger {

    /**
     * @param \Exception $exception
     */
    public function logException(\Exception $exception): void;
}
