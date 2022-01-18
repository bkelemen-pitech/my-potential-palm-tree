<?php

declare(strict_types=1);

namespace App\Security;

use App\Facade\RedisStorageFacade;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthTokenBuilder
{
    public const REDIS_PREFIX = 'USER_JWT_TOKEN_';

    protected JWTTokenManagerInterface $jwtManager;
    protected RedisStorageFacade $redisStorageFacade;
    protected int $authTTL;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        RedisStorageFacade $redisStorageFacade,
        $authTTL
    ) {
        $this->jwtManager = $jwtManager;
        $this->redisStorageFacade = $redisStorageFacade;
        $this->authTTL = (int) $authTTL;
    }

    public function createForUser(UserInterface $user, ?string $firewall = null): JWTUserToken
    {
        $payload = [
            'userId' => $user->getUserId(),
        ];

        $token = $this->jwtManager->createFromPayload($user, $payload);
        $this->setRedisToken($user, $token);

        return new JWTUserToken($user->getRoles(), $user, $token, $firewall);
    }

    public function setRedisToken(UserInterface $user, string $token)
    {
        $this->redisStorageFacade->set(self::REDIS_PREFIX . $user->getUserId(), $token, $this->authTTL);
    }
}
