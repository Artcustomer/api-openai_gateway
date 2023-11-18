<?php

namespace App\Repository;

/**
 * @author David
 */
interface IUserRepository
{

    /**
     * @param $identifier
     * @return mixed
     */
    public function findOneByIdentifier($identifier): mixed;

}

