<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\{HttpBadRequestException, HttpNotFoundException, HttpInternalServerErrorException};

abstract class AbstractAction {
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var array
     */
    protected array $args;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->perform();
        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }

    /**
     * @return Response
     * @throws HttpBadRequestException
     */
    abstract protected function perform(): Response;

    /**
     * @return array|object
     * @throws HttpBadRequestException
     */
    protected function getFormData() {
        //$input = json_decode(file_get_contents('php://input'));
        $input = \json_decode($this->request->getBody()->getContents());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name) {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException(
                $this->request,
                "Could not resolve argument `{$name}`."
            );
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     * @return Response
     */
    protected function respondWithData($data = null): Response {
        $payload = new ActionPayload(200, $data);
        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);
        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
