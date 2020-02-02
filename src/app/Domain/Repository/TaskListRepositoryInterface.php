<?php


namespace App\Domain\Repository;


use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Models\TaskList;
use Exception;

interface TaskListRepositoryInterface
{
    /**
     * @param UserId $userId
     * @param TaskListName $taskListName
     * @param TaskListStatus $taskListStatus
     * @return TaskList
     * @throws Exception
     */
    public function saveTaskList(UserId $userId, TaskListName $taskListName, TaskListStatus $taskListStatus): TaskList;
}
