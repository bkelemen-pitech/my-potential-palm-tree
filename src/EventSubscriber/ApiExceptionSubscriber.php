<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\ApiException;
use App\Traits\ExceptionMessageTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    use ExceptionMessageTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleApiExceptions', 10],
            ],
        ];
    }

    public function handleApiExceptions(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException || $exception instanceof BadRequestHttpException) {
            $httpCode = $exception->getStatusCode();
            $message = $this->buildExceptionMessage($httpCode, $exception->getMessage());
            $apiExceptionResponse = new JsonResponse($message->toArray(), $httpCode);

            $event->setResponse($apiExceptionResponse);
        }
    }
}
