<?php


namespace App\Domain\Repository;


use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Exception;

final class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function saveTask(UserId $userId, TaskListId $taskListId, TaskName $taskName, TaskStatus $taskStatus): Task
    {
        $task = new Task();
        if ($task->saveTask($userId, $taskListId, $taskName, $taskStatus) === false) {
            throw new Exception('タスクの登録に失敗しました。');
        }

        return $task;
    }
}
