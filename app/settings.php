<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $appName = 'tasks-app';
    $env = $_ENV['environment'] ?? 'dev';

    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'environment' => $env,
            'displayErrorDetails' => ($env === 'dev'),
            'logger' => [
                'name' => $appName,
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => ($env === 'dev') ? Logger::DEBUG : Logger::ERROR,
            ],
        ],
    ]);
};
