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
use App\Models\ArchiveTaskList;
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
        TaskNotificationStartDate $taskNotificationStartDate,
        TaskStatus $taskStatus
    ): Builder {
        if (Task::updateTask(
                $taskId,
                $taskListId,
                $userId,
                $taskName,
                $taskLimitDate,
                $taskNotificationStartDate,
                $taskStatus
            ) === false
        ) {
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
    public function deleteById(TaskId $taskId, UserId $userId): ArchiveTask
    {
        $task = Task::findById($taskId);
        $archiveTask = new ArchiveTask();
        if ($archiveTask->fill($task->first()->toArray())->save() === false) {
            throw new Exception('タスクの削除に失敗しました。');
        }
        $task->delete();
        return $archiveTask;
    }

    /**
     * @inheritDoc
     */
    public function deleteByIdCollection(TaskIdCollection $taskIdCollection, UserId $userId): TaskIdCollection
    {
        $taskIds = new TaskIdCollection();
        foreach ($taskIdCollection as $taskId) {
            $this->deleteById($taskId, $userId);
            $taskIds->push($taskId);
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
        $tasks = $this->findByTaskListId($taskListId, $userId)
            ->whereIn('status', $taskStatusCollection->toArray());

        $deleteCount = $tasks->count();

        $tasks->each(function ($task) {
            $archiveTask = new ArchiveTask();
            /** @var Task $task */
            $archiveTask->newQuery()->create($task->toArray());
            $task->delete();
        });

        return $deleteCount;
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
        Carbon $notificationStartDate
    ): Collection {
        return Task::findByIds($userIdCollection)
            ->with(['token'])
            ->where('notification_start_date', '<=', $notificationStartDate->format('Y/m/d'))
            ->where('status', Task::STATUS_DEFAULT)
            ->get();
    }
}
