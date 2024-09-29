<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

final class ExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $responseStdClass = new \stdClass();
        $responseStdClass->error = $exception->getMessage();
        $responseStdClass->code = $exception->getCode();

        if ($responseStdClass->code <= 0 || $responseStdClass->code >= 600) {
            $responseStdClass->code = 599;
        }

        // Collect violations in case of an ValidationException
        if ($exception instanceof ValidationException) {
            $responseStdClass->error = 'Failed to validate incoming request!';
            $responseStdClass->violations = $exception->getViolations();
        }

        $response = new JsonResponse($responseStdClass, $responseStdClass->code);
        $event->setResponse($response);
    }
}
