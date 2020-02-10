<?php


namespace App\Domain\Repository;


use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Models\TaskList;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @return Builder[]|Collection
     */
    public function findAll();
}
