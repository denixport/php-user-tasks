<?php
declare(strict_types=1);

use App\Application\Actions\Tasks\User\ListUserTasksAction;
use App\Application\Actions\Tasks\AddTaskAction;
use App\Application\Actions\Users\RegisterUserAction;
use App\Application\Actions\SummaryAction;
use Slim\App;

return function (App $app) {

    $app->get('/', SummaryAction::class);

    $app->post('/users', RegisterUserAction::class);

    $app->post('/tasks', AddTaskAction::class);

    $app->get('/tasks/user/{id}[/{date}]', ListUserTasksAction::class);
};
