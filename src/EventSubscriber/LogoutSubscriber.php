<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Enum\UserEnum;
use App\Facade\RedisStorageFacade;
use App\Security\AuthTokenBuilder;
use App\Traits\ExceptionMessageTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    use ExceptionMessageTrait;

    protected JWTTokenManagerInterface $jwtManager;
    protected RedisStorageFacade $redisStorageFacade;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        RedisStorageFacade $redisStorageFacade
    ) {
        $this->jwtManager = $jwtManager;
        $this->redisStorageFacade = $redisStorageFacade;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }

    public function onLogout(LogoutEvent $event)
    {
        $token = $event->getRequest()->headers->get('x-auth-token');
        $preAuthToken = new PreAuthenticationJWTUserToken($token);

        try {
            $payload = $this->jwtManager->decode($preAuthToken);
        } catch (JWTDecodeFailureException $exception) {
            $message = $this->buildExceptionMessage(Response::HTTP_UNAUTHORIZED, 'JWT Token not found');
            $response = new JsonResponse($message->toArray(), Response::HTTP_UNAUTHORIZED);
            $event->setResponse($response);

            return;
        }

        $key = AuthTokenBuilder::REDIS_PREFIX . $payload[UserEnum::USER_ID];
        $this->redisStorageFacade->del($key);

        $response = $event->getResponse();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
