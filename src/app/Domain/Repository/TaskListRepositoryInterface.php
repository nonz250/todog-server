<?php


namespace App\Domain\Repository;


use App\Domain\Collection\TaskStatusCollection;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Models\TaskList;
use Exception;
use Illuminate\Database\Eloquent\Builder;

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
     * @param UserId $userId
     * @return Builder
     * @throws Exception
     */
    public function findAll(UserId $userId): Builder;

    /**
     * @param UserId $userId
     * @param TaskStatusCollection $taskStatusCollection
     * @return Builder
     */
    public function findByTaskStatuses(UserId $userId, TaskStatusCollection $taskStatusCollection): Builder;
}
