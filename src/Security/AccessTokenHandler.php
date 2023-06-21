<?php

namespace App\Security;

use App\Repository\JsonAccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{

    private JsonAccessTokenRepository $repository;

    /**
     * Constructor
     *
     * @param JsonAccessTokenRepository $repository
     */
    public function __construct(JsonAccessTokenRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $accessToken
     * @return UserBadge
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $token = $this->repository->findOneByIdentifier($accessToken);

        if (null === $token || !$token->getEnabled()) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($token->getUsername());
    }
}
