<?php
namespace App\Listeners;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        
        if (get_class($exception) == "App\Exception\ValidationException") {
            $event->setResponse($this->setCustomResponse(Response::HTTP_BAD_REQUEST, $exception));
            return;
        }
        
        if (in_array("Respect\Validation\Exceptions\ExceptionInterface", class_implements($exception))) {
            $event->setResponse($this->setCustomResponse(Response::HTTP_BAD_REQUEST, $exception));
            return;
        }

        $event->setResponse((new Response())->setContent($exception->getMessage()));
    }

    public function setCustomResponse(int $code, \Exception $exception)
    {
        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');

        $response->setContent(json_encode([
            "code" => $code,
            "message" => $exception->getMessage()
        ]));

        $response->setStatusCode($code);

        return $response;
    }
}