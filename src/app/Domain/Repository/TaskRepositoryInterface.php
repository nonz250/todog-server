<?php
declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\Collection\TaskIdCollection;
use App\Domain\Collection\TaskStatusCollection;
use App\Domain\Collection\UserIdCollection;
use App\Domain\ValueObject\TaskId;
use App\Domain\ValueObject\TaskLimitDate;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskNotificationStartDate;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\ArchiveTask;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
     * @param TaskLimitDate $taskLimitDate
     * @param TaskNotificationStartDate $taskNotificationStartDate
     * @param TaskStatus $taskStatus
     * @return Builder
     * @throws Exception
     */
    public function updateTask(
        TaskId $taskId,
        TaskListId $taskListId,
        UserId $userId,
        TaskName $taskName,
        TaskLimitDate $taskLimitDate,
        TaskNotificationStartDate $taskNotificationStartDate,
        TaskStatus $taskStatus
    ): Builder;

    /**
     * @param TaskId $taskId
     * @param UserId $userId
     * @param TaskStatus $taskStatus
     * @return Builder
     * @throws Exception
     */
    public function updateStatusById(TaskId $taskId, UserId $userId, TaskStatus $taskStatus): Builder;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @return Builder
     */
    public function findByTaskListId(TaskListId $taskListId, UserId $userId): Builder;

    /**
     * @param TaskId $taskId
     * @param UserId $userId
     * @return ArchiveTask
     * @throws Exception
     */
    public function deleteById(TaskId $taskId, UserId $userId): ArchiveTask;

    /**
     * @param TaskIdCollection $taskIdCollection
     * @param UserId $userId
     * @return TaskIdCollection
     * @throws Exception
     */
    public function deleteByIdCollection(TaskIdCollection $taskIdCollection, UserId $userId): TaskIdCollection;

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @param TaskStatusCollection $taskStatus
     * @return int
     * @throws Exception
     */
    public function deleteByTaskListId(TaskListId $taskListId, UserId $userId, TaskStatusCollection $taskStatus): int;

    /**
     * @param UserIdCollection $userIdCollection
     * @return Collection
     */
    public function findByUserIds(UserIdCollection $userIdCollection): Collection;

    /**
     * @param UserIdCollection $userIdCollection
     * @param Carbon $limitDate
     * @return Collection
     */
    public function findByUserIdsAndLimitDateWithFcmToken(
        UserIdCollection $userIdCollection,
        Carbon $limitDate
    ): Collection;
}
