<?php
declare(strict_types=1);

namespace Tests\Functional\Application\Actions\Tasks\User;

use App\Domain\Common\Values\Date;
use App\Domain\Tasks\{TaskRepository, UserTasksQuery};
use App\Infrastructure\Tasks\{InMemTaskRepository, InMemUserTasksQuery};
use DI\Container;
use Tests\Functional\Application\AppTestCase;

class ListUserTasksAction extends AppTestCase {

    private $app;

    private const TEST_DATA = [
        'users' => [
            [
                'id' => 1,
                'email' => 'alice@example.com',
                'first_name' => 'Alice',
                'last_name' => 'Doe',
            ],
            [
                'id' => 2,
                'email' => 'bob@example.com',
                'first_name' => 'Bob',
                'last_name' => 'Doe',
            ],
        ],
    ];

    public function setUp():void {
        $this->app = $this->getApp();

        $taskRepo = new InMemTaskRepository();

        /** @var Container $container */
        $container = $this->app->getContainer();
        $container->set(TaskRepository::class, $taskRepo);
        $container->set(UserTasksQuery::class, new InMemUserTasksQuery($taskRepo));
    }

    private function post(string $path, array $data) {
        $payload = \json_encode($data);

        $request = $this->createRequest('POST', $path);
        $request->getBody()->write($payload);
        $request->getBody()->rewind();

        $response = $this->app->handle($request);
        $respPayload = (string) $response->getBody();

        return \json_decode($respPayload);
    }

    private function get(string $path) {
        $request = $this->createRequest('GET', $path);
        $response = $this->app->handle($request);
        $respPayload = (string) $response->getBody();

        return \json_decode($respPayload);
    }


    public function testUserCanListDailyTasks() {
        // register user
        $user = [
            'email' => 'alice@example.com',
            'first_name' => 'Alice',
            'last_name' => 'Doe',
        ];
        $res = $this->post('/users', $user);
        $userId = $res->data->id;

        // add one task for today
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $year = (int) $now->format('Y');

        $task = [
            'user_id' => $userId,
            'date' => $now->format('Y-m-d'),
            'priority' => 'URGENT',
            'title' => "My task for today",
            'description' => "Some description",
        ];
        $this->post('/tasks', $task);

        // add random tasks in the future
        $next = $now->modify('first day of next month');
        $nextYear = (int) $next->format( 'Y' );
        $nextMonth = (int) $next->format( 'n' );

        $days = [1, 2, 2, 2, 3, 4, 5, 5];
        foreach ($days as $nextDay) {
            $date = sprintf('%4d-%02d-%02d', $nextYear, $nextMonth, $nextDay);
            $task = [
                'user_id' => $userId,
                'date' => $date,
                'priority' => 'AVERAGE',
                'title' => "My task for {$date}",
                'description' => "Some description",
            ];
            $this->post('/tasks', $task);
        }

        // list user tasks for today
        $res = $this->get("/tasks/user/{$userId}/current");

        $this->assertEquals(200, $res->statusCode);
        // should be only one task for today
        //$this->assertEquals(1, \count($res->data));
    }
}