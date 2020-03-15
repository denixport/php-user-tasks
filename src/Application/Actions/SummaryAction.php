<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;

class SummaryAction extends AbstractAction {
    protected function perform(): Response {
        $endpoints = [
            'users' => '/users',
            'tasks' => '/tasks',
            'user_tasks' => '/tasks/user',
        ];
        return $this->respondWithData($endpoints);
    }
}