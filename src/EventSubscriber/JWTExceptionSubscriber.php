<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\DTO\ErrorMessageDTO;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JWTExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_INVALID => [
                'handleJWTExceptions'
            ],
            Events::JWT_EXPIRED => [
                'handleJWTExceptions'
            ],
            Events::JWT_NOT_FOUND => [
                'handleJWTExceptions'
            ],
        ];
    }

    public function handleJWTExceptions(AuthenticationFailureEvent $event): void
    {
        $exception = $event->getException();
        if ($exception instanceof AuthenticationException) {
            $message = $this->buildAuthExceptionMessage($exception);
            $apiExceptionResponse = new JsonResponse($message->toArray(), Response::HTTP_UNAUTHORIZED);

            $event->setResponse($apiExceptionResponse);
        }
    }

    protected function buildAuthExceptionMessage(AuthenticationException $exception): ErrorMessageDTO
    {
        $message = new ErrorMessageDTO();
        $message
            ->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->setBody(json_decode($exception->getMessage(), true))
            ->setError($exception->getMessageKey())
            ->setStatus('error');

        return $message;
    }
}
