<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\{ActionError, ActionPayload};
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\{HttpBadRequestException,
    HttpException,
    HttpForbiddenException,
    HttpMethodNotAllowedException,
    HttpNotFoundException,
    HttpNotImplementedException,
    HttpUnauthorizedException};
use Slim\Handlers\ErrorHandler as SlimErrorHandler;

class HttpErrorHandler extends SlimErrorHandler {
    /**
     * @inheritdoc
     */
    protected function respond(): Response {
        $exception = $this->exception;
        $statusCode = 500;

        $type = ActionError::SERVER_ERROR;
        $message = '';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $message = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $type = ActionError::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = ActionError::METHOD_NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = ActionError::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = ActionError::INSUFFICIENT_PRIVILEGES;
            } elseif ($exception instanceof HttpBadRequestException) {
                $type = ActionError::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = ActionError::NOT_IMPLEMENTED;
            }
        } else if ($this->displayErrorDetails &&
            ($exception instanceof \Exception || $exception instanceof \Throwable)) {
            $message = $exception->getMessage();
        }

        $error = new ActionError($type, $message);
        $payload = new ActionPayload($statusCode, $error);

        $encoded = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encoded);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
