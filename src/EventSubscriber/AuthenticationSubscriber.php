<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Security\AuthTokenBuilder;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public const EXTENDED_TOKEN_ROUTES_EXCLUSION = ['app.v1.login', 'app.v1.logout'];

    public ?UserInterface $user = null;
    protected AuthTokenBuilder $authTokenBuilder;

    public function __construct(AuthTokenBuilder $authTokenBuilder)
    {
        $this->authTokenBuilder = $authTokenBuilder;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_AUTHENTICATED => [
                'onAuthenticatedAccess'
            ],
            KernelEvents::RESPONSE => [
                'onAuthenticatedResponse'
            ],
            Events::JWT_CREATED => [
                'onJWTCreated'
            ],
        ];
    }

    public function onAuthenticatedResponse(ResponseEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');
        $shouldExtendToken = !in_array($route, self::EXTENDED_TOKEN_ROUTES_EXCLUSION);
        if ($shouldExtendToken && $this->user) {
            $token = $event->getRequest()->headers->get('X-Auth-Token');
            $this->authTokenBuilder->setRedisToken($this->user, $token);
        }
    }

    public function onAuthenticatedAccess(JWTAuthenticatedEvent $event)
    {
        $this->user = $event->getToken()->getUser();
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $expiration = new \DateTime('tomorrow 03:00', new \DateTimeZone('UTC'));

        $payload = $event->getData();
        $payload['exp'] = $expiration->getTimestamp();

        $event->setData($payload);
    }
}
