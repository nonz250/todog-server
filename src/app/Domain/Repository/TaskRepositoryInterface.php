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

interface TaskRepositoryInterface
{
    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @param TaskName $taskName
     * @param TaskStatus $taskStatus
     * @return Task
     * @throws Exception
     */
    public function saveTask(TaskListId $taskListId, UserId $userId, TaskName $taskName, TaskStatus $taskStatus): Task;

    /**
     * @param TaskId $taskId
     * @param UserId $userId
     * @param TaskListId $taskListId
     * @param TaskName $taskName
     * @param TaskStatus $taskStatus
     * @return Builder
     * @throws Exception
     */
    public function updateTask(
        TaskId $taskId,
        TaskListId $taskListId,
        UserId $userId,
        TaskName $taskName,
        TaskStatus $taskStatus
    ): Builder;

    /**
     * @param TaskId $taskId
     * @param UserId $userId
     * @param TaskStatus $taskStatus
     * @return bool
     * @throws Exception
     */
    public function updateStatus(TaskId $taskId, UserId $userId, TaskStatus $taskStatus): bool;
}
