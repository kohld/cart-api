<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!str_starts_with($event->getRequest()->getPathInfo(), '/api/')) {
            return;
        }

        $exception = $event->getThrowable();
        $previous = $exception->getPrevious();

        if ($previous instanceof ValidationFailedException) {
            $violations = [];
            foreach ($previous->getViolations() as $violation) {
                $violations[] = ['field' => $violation->getPropertyPath(), 'message' => $violation->getMessage()];
            }

            $event->setResponse($this->errorResponse(
                'Unprocessable Content',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $violations
            ));

            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $event->setResponse($this->errorResponse(
                Response::$statusTexts[$statusCode] ?? 'Error',
                $statusCode,
                headers: $exception->getHeaders(),
            ));

            return;
        }

        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        $event->setResponse($this->errorResponse('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR));
    }

    private function errorResponse(string $error, int $status, array $violations = [], array $headers = []): JsonResponse
    {
        return new JsonResponse(
            ['error' => $error, 'violations' => $violations],
            $status,
            $headers,
        );
    }
}
