<?php

namespace App\Security;

use App\Repository\JsonAccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author David
 */
class AccessTokenHandler implements AccessTokenHandlerInterface
{

    private JsonAccessTokenRepository $repository;
    private TranslatorInterface $translator;

    /**
     * Constructor
     *
     * @param JsonAccessTokenRepository $repository
     * @param TranslatorInterface $translator
     */
    public function __construct(JsonAccessTokenRepository $repository, TranslatorInterface $translator)
    {
        $this->repository = $repository;
        $this->translator = $translator;
    }

    /**
     * @param string $accessToken
     * @return UserBadge
     * @throws \Exception
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $token = $this->repository->findOneByIdentifier($accessToken);

        if (null === $token || !$token->getEnabled()) {
            throw new BadCredentialsException($this->translator->trans('login.error.invalid_credentials'));
        }

        return new UserBadge($token->getUsername());
    }
}
