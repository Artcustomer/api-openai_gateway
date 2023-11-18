<?php

namespace App\Entity;

/**
 * @author David
 */
class AbstractEntity
{

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return \stdClass
     */
    public function toObject(): \stdClass
    {
        return new \stdClass();
    }
}
