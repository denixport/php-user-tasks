<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks\User;

use App\Application\Actions\AbstractAction;
use App\Domain\Common\Values\Date;
use App\Domain\Tasks\UserTasksQuery as Query;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface as Logger;

class ListUserTasksAction extends AbstractAction {
    /**
     * @var Query
     */
    private Query $query;

    public function __construct(Query $query, Logger $logger) {
        $this->query = $query;

        parent::__construct($logger);
    }

    public function perform(): Response {
        $userId = (int)$this->resolveArg('id');
        $this->query->setUserId($userId);

        $dateArg = $this->resolveArg('date');
        if ($dateArg === 'current') {
            $date = Date::current();
        } else {
            list ($y, $m, $d) = explode('-', $dateArg, 3);
            $date = Date::create((int)$y, (int)$m, (int)$d);
        }
        $tasks = $this->query->getTasksByDate($date);
        return $this->respondWithData($tasks);
    }
}