<?php
declare(strict_types=1);

namespace App\Domain\UseCase\TaskList;


use App\Domain\Collection\TaskStatusCollection;
use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class DeleteTaskListUseCase
{
    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * DeleteTaskListUseCase constructor.
     *
     * @param TaskListRepositoryInterface $taskListRepository
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        TaskListRepositoryInterface $taskListRepository,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->taskListRepository = $taskListRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(int $id)
    {
        $taskListId = new TaskListId($id);
        $userId = new UserId(Auth::id());
        $taskStatusCollection = new TaskStatusCollection([
            new TaskStatus(Task::STATUS_DEFAULT),
            new TaskStatus(Task::STATUS_COMPLETED),
        ]);

        $updateCountTask = DB::transaction(function () use (
            $taskListId,
            $userId,
            $taskStatusCollection
        ) {
            $updateCountTask = $this->taskRepository
                ->deleteByTaskListId($taskListId, $userId, $taskStatusCollection);

            $this->taskListRepository
                ->deleteById($taskListId, $userId);

            return $updateCountTask;
        });

        return response()->json([
            'result' => true,
            'task_delete_num' => $updateCountTask,
            'id' => $taskListId->toInt(),
        ]);
    }
}
