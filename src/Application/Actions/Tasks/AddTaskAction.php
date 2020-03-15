<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use App\Application\Actions\AbstractAction;
use App\Domain\Common\Values\Date;
use App\Domain\Tasks\{Task, TaskDescription, TaskPriority, TaskRepository};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface as Logger;

class AddTaskAction extends AbstractAction {
    private TaskRepository $repo;

    public function __construct(TaskRepository $repo, Logger $logger) {
        $this->repo = $repo;
        parent::__construct($logger);
    }

    protected function perform(): Response {
        $data = $this->getFormData();
        $userId = (int)$data->user_id;
        $date = Date::parse($data->date);
        $priority = TaskPriority::parse($data->priority);
        $descr = new TaskDescription($data->title, $data->description);

        $task = Task::createNew($userId, $date, $descr, $priority);
        $this->repo->store($task);

        return $this->respondWithData(['id' => $task->id]);
    }
}