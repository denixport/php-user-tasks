<?php

namespace Tests\Unit\Domain\Tasks;

use App\Domain\Common\Values\DateTime;
use App\Domain\Tasks\{TaskData, TaskPriority, TaskDescription, TaskStatus};
use PHPUnit\Framework\TestCase;

class TaskDataTest extends TestCase {

    public function testCanSerializeToAndFromJson() {
        $data = new TaskData();
        $data->id = 999999;
        $data->userId = 999;
        $data->title = 'My new Task';
        $data->description = 'My new Task description';
        $data->time = DateTime::fromTimestamp(0);
        $data->priority = TaskPriority::of(TaskPriority::URGENT);
        $data->status = TaskStatus::of(TaskStatus::IN_PROGRESS);

        $encoded = \json_encode($data);

        $expected = '{"id":999999,"userId":999,"time":"1970-01-01T00:00:00+0000",'.
            '"priority":"URGENT","status":"IN_PROGRESS","title":"My new Task",'.
            '"description":"My new Task description"}';

        $this->assertEquals($expected, $encoded);
    }
}
