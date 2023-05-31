<?php

namespace App\Repository;

interface IUserRepository
{

    public function findOneByIdentifier($identifier);

}

