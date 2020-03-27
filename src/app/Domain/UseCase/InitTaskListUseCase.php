<?php
declare(strict_types=1);

namespace App\Domain\UseCase;


use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListSort;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Models\TaskList;
use Exception;

final class InitTaskListUseCase
{
    /** @var string */
    private const INIT_TASK_LIST_NAME = '日常';

    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    public function __construct(TaskListRepositoryInterface $taskListRepository)
    {
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @param UserId $userId
     * @return TaskList
     * @throws Exception
     */
    public function __invoke(UserId $userId)
    {
        $taskListName = new TaskListName(self::INIT_TASK_LIST_NAME);
        $taskListSort = new TaskListSort(0);
        $taskListStatus = new TaskListStatus(TaskList::STATUS_ENABLED);
        return $this->taskListRepository->saveTaskList($userId, $taskListName, $taskListSort, $taskListStatus);
    }
}
