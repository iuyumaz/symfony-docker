<?php

namespace App\Listener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $response = new Response();
        $exception = $event->getThrowable();

        if ($exception instanceof BadRequestHttpException) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $errorMessage = $exception->getMessage();
        } else if ($exception instanceof NotFoundHttpException) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $errorMessage = $exception->getMessage();
        } else if ($exception instanceof ValidationException) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $errorMessage = $exception->getMessage();
            $errors = $exception->getErrors();
        } else if ($exception instanceof HttpException) {
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
            $errorMessage = $exception->getMessage();
        } else {
//            var_dump(get_class($exception));
//            var_dump($exception->getFile());
//            var_dump($exception->getLine());
//            var_dump($exception->getMessage());
//            exit;
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $errorMessage = 'Undefined exception handled. Please contact with administrator.';
        }
        $content = [
            'code' => $response->getStatusCode(),
            'errorMessage' => $errorMessage,
        ];
        if (isset($errors)) {
            $content['errors'] = $errors;
        }

        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $response->setContent(json_encode($content));

        $event->setResponse($response);
    }


}
