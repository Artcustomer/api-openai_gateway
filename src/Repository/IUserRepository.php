<?php

namespace App\Repository;

interface IUserRepository
{

    /**
     * @param $identifier
     * @return mixed
     */
    public function findOneByIdentifier($identifier): mixed;

}

