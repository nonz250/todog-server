<?php


namespace App\Domain\Repository;


use App\Domain\Collection\TaskListStatusCollection;
use App\Domain\Collection\TaskStatusCollection;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Models\TaskList;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class TaskListRepository implements TaskListRepositoryInterface
{
    /** @var array */
    private $relations = ['tasks'];

    /**
     * @inheritDoc
     */
    public function saveTaskList(UserId $userId, TaskListName $taskListName, TaskListStatus $taskListStatus): TaskList
    {
        $taskList = new TaskList();
        if ($taskList->saveTaskList($userId, $taskListName, $taskListStatus) === false) {
            throw new Exception('タスクリストの初期化に失敗しました。');
        }

        return $taskList;
    }

    /**
     * @param UserId $userId
     * @return Builder
     */
    public function findAll(UserId $userId): Builder
    {
        return TaskList::findByUserId($userId)
            ->with($this->relations);
    }

    /**
     * @inheritDoc
     */
    public function findByTaskStatuses(
        UserId $userId,
        TaskListStatusCollection $taskListStatusCollection,
        TaskStatusCollection $taskStatusCollection
    ): Builder {
        return TaskList::findByStatus($userId, $taskListStatusCollection)
            ->with([
                'tasks' => function ($query) use ($taskStatusCollection) {
                    /** @var HasMany $query */
                    $query->whereIn('status', $taskStatusCollection->toArray());
                }
            ]);
    }

    /**
     * @inheritDoc
     */
    public function findById(TaskListId $taskListId, UserId $userId): Builder
    {
        return TaskList::findById($taskListId, $userId)
            ->with($this->relations);
    }

    /**
     * @inheritDoc
     */
    public function updateStatusById(TaskListId $taskListId, UserId $userId, TaskListStatus $taskListStatus): Builder
    {
        if (TaskList::updateStatusById($taskListId, $userId, $taskListStatus) === false) {
            throw new Exception('タスクリストの更新に失敗しました。');
        }
        return TaskList::findById($taskListId, $userId)
            ->with($this->relations);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(TaskListId $taskListId, UserId $userId): int
    {
        return TaskList::findById($taskListId, $userId)
            ->update([
                'status' => TaskList::STATUS_DISABLED,
            ]);
    }
}
