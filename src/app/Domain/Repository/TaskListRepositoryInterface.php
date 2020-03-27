<?php
declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\Collection\TaskListStatusCollection;
use App\Domain\Collection\TaskStatusCollection;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListSort;
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
     * @param TaskListSort $taskListSort
     * @param TaskListStatus $taskListStatus
     * @return TaskList
     * @throws Exception
     */
    public function saveTaskList(
        UserId $userId,
        TaskListName $taskListName,
        TaskListSort $taskListSort,
        TaskListStatus $taskListStatus
    ): TaskList;

    /**
     * @param UserId $userId
     * @return Builder
     * @throws Exception
     */
    public function findAll(UserId $userId): Builder;

    /**
     * @param UserId $userId
     * @param TaskListStatusCollection $taskListStatusCollection
     * @param TaskStatusCollection $taskStatusCollection
     * @return Builder
     */
    public function findByTaskStatuses(
        UserId $userId,
        TaskListStatusCollection $taskListStatusCollection,
        TaskStatusCollection $taskStatusCollection
    ): Builder;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @return Builder
     */
    public function findById(TaskListId $taskListId, UserId $userId): Builder;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @param TaskListName $taskListName
     * @param TaskListSort $taskListSort
     * @return TaskList
     * @throws Exception
     */
    public function updateById(
        TaskListId $taskListId,
        UserId $userId,
        TaskListName $taskListName,
        TaskListSort $taskListSort
    ): TaskList;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @return int
     */
    public function deleteById(TaskListId $taskListId, UserId $userId): int;
}
