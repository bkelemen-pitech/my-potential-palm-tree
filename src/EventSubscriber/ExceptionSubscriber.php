<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Enum\ExceptionMessageEnum;
use App\Enum\ResponseEnum;
use App\Traits\ExceptionMessageTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

class ExceptionSubscriber implements EventSubscriberInterface
{
    use ExceptionMessageTrait;

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $appLogger)
    {
        $this->logger = $appLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleExceptions'],
            ],
        ];
    }

    public function handleExceptions(ExceptionEvent $event): void
    {
        $response = $this->buildResponse($event->getThrowable());
        $event->setResponse(
            new JsonResponse($response[ResponseEnum::BODY]->toArray(), $response[ResponseEnum::STATUS_CODE])
        );
    }

    protected function buildResponse(Throwable $exception): array
    {
        $this->logger->critical($exception->getMessage(), [$exception->getTrace()]);

        if ($exception instanceof AuthenticationException) {
            return [
                ResponseEnum::BODY => $this->buildExceptionMessage(
                    Response::HTTP_UNAUTHORIZED,
                    ExceptionMessageEnum::AUTHENTICATION_REQUIRED
                ),
                ResponseEnum::STATUS_CODE => Response::HTTP_UNAUTHORIZED,
            ];
        }

        if ($exception instanceof NotFoundHttpException) {
            return [
                ResponseEnum::BODY => $this->buildExceptionMessage(
                    Response::HTTP_NOT_FOUND,
                    ExceptionMessageEnum::PAGE_NOT_FOUND
                ),
                ResponseEnum::STATUS_CODE => Response::HTTP_NOT_FOUND,
            ];
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return [
                ResponseEnum::BODY => $this->buildExceptionMessage(
                    $exception->getStatusCode(),
                    $exception->getMessage()
                ),
                ResponseEnum::STATUS_CODE => Response::HTTP_NOT_FOUND,
            ];
        }

        return [
            ResponseEnum::BODY => $this->buildExceptionMessage(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ExceptionMessageEnum::INTERNAL_SERVER_ERROR
            ),
            ResponseEnum::STATUS_CODE => Response::HTTP_INTERNAL_SERVER_ERROR,
        ];
    }
}
