<?php
declare(strict_types=1);

use App\Domain\Tasks\{TaskRepository, UserTasksQuery};
use App\Domain\Users\UserRepository;
use App\Infrastructure\Tasks\{InMemTaskRepository, InMemUserTasksQuery};
use App\Infrastructure\Users\InMemUserRepository;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        UserRepository::class => function (ContainerInterface $c) {
            return new InMemUserRepository();
        },
        UserTasksQuery::class => function (ContainerInterface $c) {
            return new InMemUserTasksQuery($c->get(TaskRepository::class));
        },
        TaskRepository::class => function (ContainerInterface $c) {
            return new InMemTaskRepository();
        },
    ]);
};
