<?php
declare(strict_types=1);

use App\Application\Emitters\ResponseEmitter;
use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settingsFunc = require __DIR__ . '/../app/settings.php';
$settingsFunc($containerBuilder);

if (isset($_ENV['environment']) && $_ENV['environment'] !== 'dev') {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
    $containerBuilder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
}

// Set up dependencies
$dependencies = require __DIR__ . '/../app/deps.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register routes
$routesFunc = require __DIR__ . '/../app/routes.php';
$routesFunc($app);

// Register middleware
$app->addRoutingMiddleware();
$middlewareFunc = require __DIR__ . '/../app/middleware.php';
$middlewareFunc($app);

// Create Request object from globals
//$requestCreator = ServerRequestCreatorFactory::create();
$request = ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Error Handler
//$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $app->getResponseFactory());

// Create Shutdown Handler
register_shutdown_function(
    new ShutdownHandler($request, $errorHandler, $displayErrorDetails)
);

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$response = $app->handle($request);
//$responseEmitter = new ResponseEmitter();
(new ResponseEmitter())->emit($response);
