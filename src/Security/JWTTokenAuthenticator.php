<?php

declare(strict_types=1);

namespace App\Security;

use App\Enum\UserEnum;
use App\Facade\RedisStorageFacade;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as LexikJWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class JWTTokenAuthenticator extends LexikJWTTokenAuthenticator
{
    protected RedisStorageFacade $redisStorageFacade;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        TokenExtractorInterface $tokenExtractor,
        TokenStorageInterface $preAuthenticationTokenStorage,
        RedisStorageFacade $redisStorageFacade
    ) {
        parent::__construct($jwtManager, $dispatcher, $tokenExtractor, $preAuthenticationTokenStorage);
        $this->redisStorageFacade = $redisStorageFacade;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $key = AuthTokenBuilder::REDIS_PREFIX . $credentials->getPayload()[UserEnum::USER_ID];

        return $this->redisStorageFacade->has($key)
            && $this->redisStorageFacade->get($key) === $credentials->getCredentials();
    }

    public function getLoggedUserData()
    {
        return $this->getJwtManager()->decode($this->getPreAuthenticationTokenStorage()->getToken());
    }
}
