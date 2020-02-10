<?php


namespace App\Domain\UseCase;


use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Exception;

final class InitTaskUseCase
{
    /** @var string */
    private const INIT_TASK_NAME_1 = 'メモ帳を買う';

    /** @var string */
    private const INIT_TASK_NAME_2 = 'ティッシュを買う';

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param UserId $userId
     * @param TaskListId $taskListId
     * @throws Exception
     */
    public function __invoke(UserId $userId, TaskListId $taskListId): void
    {
        $taskName = new TaskName(self::INIT_TASK_NAME_1);
        $taskStatus = new TaskStatus(Task::STATUS_DEFAULT);
        $this->taskRepository->saveTask($userId, $taskListId, $taskName, $taskStatus);

        $taskName = new TaskName(self::INIT_TASK_NAME_2);
        $taskStatus = new TaskStatus(Task::STATUS_DISABLED);
        $this->taskRepository->saveTask($userId, $taskListId, $taskName, $taskStatus);
    }
}
