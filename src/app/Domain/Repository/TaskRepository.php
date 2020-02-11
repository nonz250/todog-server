<?php


namespace App\Domain\Repository;


use App\Domain\ValueObject\TaskId;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Builder;

final class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function saveTask(TaskListId $taskListId, UserId $userId, TaskName $taskName, TaskStatus $taskStatus): Task
    {
        $task = new Task();
        if ($task->saveTask($taskListId, $userId, $taskName, $taskStatus) === false) {
            throw new Exception('タスクの登録に失敗しました。');
        }

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function updateTask(
        TaskId $taskId,
        TaskListId $taskListId,
        UserId $userId,
        TaskName $taskName,
        TaskStatus $taskStatus
    ): Builder {
        if (Task::updateTask($taskId, $taskListId, $userId, $taskName, $taskStatus) === false) {
            throw new Exception('タスクの更新に失敗しました。');
        }
        return Task::findById($taskId);
    }

    /**
     * @inheritDoc
     */
    public function updateStatus(TaskId $taskId, UserId $userId, TaskStatus $taskStatus): bool
    {
        $result = Task::updateStatus($taskId, $userId, $taskStatus);
        if ($result === false) {
            throw new Exception('タスクの削除に失敗しました。');
        }
        return $result;
    }
}
