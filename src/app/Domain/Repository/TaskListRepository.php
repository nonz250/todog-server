<?php


namespace App\Domain\Repository;


use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Models\TaskList;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Builder[]|Collection
     */
    public function findAll()
    {
        return TaskList::with($this->relations)
            ->get();
    }
}
