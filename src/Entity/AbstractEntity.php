<?php

namespace App\Entity;

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
