<?php
declare(strict_types=1);

namespace App\Domain\UseCase\Task;


use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\TaskId;
use App\Domain\ValueObject\TaskLimitDate;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskNotificationStartDate;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\UpdateTaskRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class UpdateTaskUseCase
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * UpdateTaskUseCase constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param int $id
     * @param UpdateTaskRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(int $id, UpdateTaskRequest $request): JsonResponse
    {
        $taskId = new TaskId((int) $id);
        $userId = new UserId(Auth::id());
        $taskListId = new TaskListId((int) $request->get('task_list_id'));
        $taskName = new TaskName($request->get('name'));
        $taskLimitDate = new TaskLimitDate($request->get('limit_date'));
        $taskNotificationStartDate = new TaskNotificationStartDate($request->get('notification_start_date'));
        $taskStatus = new TaskStatus((int) $request->get('status'));

        $task = $this->taskRepository
            ->updateTask(
                $taskId,
                $taskListId,
                $userId,
                $taskName,
                $taskLimitDate,
                $taskNotificationStartDate,
                $taskStatus
            )->first();

        return response()->json($task);
    }
}
