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
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
        TaskLimitDate $taskLimitDate,
        TaskStatus $taskStatus
    ): Builder {
        if (Task::updateTask($taskId, $taskListId, $userId, $taskName, $taskLimitDate, $taskStatus) === false) {
            throw new Exception('タスクの更新に失敗しました。');
        }
        return Task::findById($taskId);
    }

    /**
     * @inheritDoc
     */
    public function updateStatusById(TaskId $taskId, UserId $userId, TaskStatus $taskStatus): Builder
    {
        if (Task::updateStatusById($taskId, $userId, $taskStatus) === false) {
            throw new Exception('タスクの更新に失敗しました。');
        }
        return Task::findById($taskId);
    }

    /**
     * @inheritDoc
     */
    public function findByTaskListId(TaskListId $taskListId, UserId $userId): Builder
    {
        return Task::findByTaskListId($taskListId, $userId);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(TaskId $taskId, UserId $userId): Builder
    {
        return $this->updateStatusById($taskId, $userId, new TaskStatus(Task::STATUS_DISABLED));
    }

    /**
     * @inheritDoc
     */
    public function deleteByIdCollection(TaskIdCollection $taskIdCollection, UserId $userId): TaskIdCollection
    {
        $taskIds = new TaskIdCollection();
        foreach ($taskIdCollection as $taskId) {
            $taskIds->push(
                new TaskId(
                    (int) $this
                        ->updateStatusById($taskId, $userId, new TaskStatus(Task::STATUS_DISABLED))
                        ->first('id')
                        ->getAttribute('id')
                )
            );
        }
        return $taskIds;
    }

    /**
     * @inheritDoc
     */
    public function deleteByTaskListId(
        TaskListId $taskListId,
        UserId $userId,
        TaskStatusCollection $taskStatusCollection
    ): int {
        return $this->findByTaskListId($taskListId, $userId)
            ->whereIn('status', $taskStatusCollection->toArray())
            ->update([
                'status' => Task::STATUS_DISABLED,
            ]);
    }

    /**
     * @inheritDoc
     */
    public function findByUserIds(UserIdCollection $userIdCollection): Collection
    {
        return Task::findByIds($userIdCollection)->get();
    }

    /**
     * @inheritDoc
     */
    public function findByUserIdsAndLimitDateWithFcmToken(
        UserIdCollection $userIdCollection,
        Carbon $limitDate
    ): Collection {
        return Task::findByIds($userIdCollection)
            ->with(['token'])
            ->where('limit_date', '<=', $limitDate->format('Y/m/d'))
            ->where('status', Task::STATUS_DEFAULT)
            ->get();
    }
}
