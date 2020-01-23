<?php
namespace App\Listeners;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Respect\Validation\Exceptions\ExceptionInterface;

class ApiExceptionListeners
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new Response();

        if (in_array(ExceptionInterface::class, class_implements($exception))) {
            $response->headers->set('Content-Type', 'application/json');

            $response->setContent(json_encode([
                "code" => Response::HTTP_BAD_REQUEST,
                "message" => $exception->getMessage()
            ]));

            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $event->setResponse($response);
    }
}