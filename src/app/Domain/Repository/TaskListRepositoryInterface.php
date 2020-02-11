<?php


namespace App\Domain\Repository;


use App\Domain\Collection\TaskStatusCollection;
use App\Domain\ValueObject\TaskListId;
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

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @return Builder
     */
    public function findById(TaskListId $taskListId, UserId $userId): Builder;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @param TaskListStatus $taskListStatus
     * @return Builder
     * @throws Exception
     */
    public function updateStatusById(TaskListId $taskListId, UserId $userId, TaskListStatus $taskListStatus): Builder;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @return int
     */
    public function deleteById(TaskListId $taskListId, UserId $userId): int;
}
