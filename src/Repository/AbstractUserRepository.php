<?php

namespace App\Repository;

use App\Factory\UserFactory;

abstract class AbstractUserRepository
{

    protected UserFactory $userFactory;

    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }
}