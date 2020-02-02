<?php


namespace App\Domain\Repository;


use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Exception;

interface TaskRepositoryInterface
{
    /**
     * @param UserId $userId
     * @param TaskListId $taskListId
     * @param TaskName $taskName
     * @param TaskStatus $taskStatus
     * @return Task
     * @throws Exception
     */
    public function saveTask(UserId $userId, TaskListId $taskListId, TaskName $taskName, TaskStatus $taskStatus): Task;
}
