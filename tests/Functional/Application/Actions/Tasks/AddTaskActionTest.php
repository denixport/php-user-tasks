<?php
declare(strict_types=1);

namespace Tests\Functional\Application\Actions\Tasks;

use DI\Container;
use Tests\Functional\Application\AppTestCase;
use App\Infrastructure\Tasks\InMemTaskRepository;
use App\Domain\Tasks\TaskRepository;

class AddTaskActionTest extends AppTestCase {

    private function doPostRequest(array $taskData) {
        $app = $this->getApp();

        /** @var Container $container */
        $container = $app->getContainer();
        $container->set(TaskRepository::class, new InMemTaskRepository());

        $payload = \json_encode($taskData);

        $request = $this->createRequest('POST', '/tasks');
        $request->getBody()->write($payload);
        $request->getBody()->rewind();

        $response = $app->handle($request);
        $respPayload = (string) $response->getBody();
        $respObj = \json_decode($respPayload);

        return $respObj;
    }

    public function testTaskCanBeAdded() {
        $task = [
            'user_id' => 999,
            'date' => '2100-01-01',
            'title' => 'My new task',
            'description' => 'My new task description',
            'priority' => 'LOW',
        ];

        $respObj = $this->doPostRequest($task);

        $this->assertIsObject($respObj);
        $this->assertIsObject($respObj->data);

        $this->assertEquals(200, $respObj->statusCode);
        $this->assertTrue($respObj->data->id > 0);
    }

    public function testCanNotAddActiveTasksInThePast() {
        $task = [
            'user_id' => 888,
            'date' => '2020-01-01',
            'title' => 'My new task',
            'description' => 'My new task description',
            'priority' => 'URGENT',
        ];
        $this->expectException(\Exception::class);
        $respObj = $this->doPostRequest($task);
    }
}