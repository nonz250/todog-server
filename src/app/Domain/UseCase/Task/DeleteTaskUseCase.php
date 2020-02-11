<?php


namespace App\Domain\UseCase\Task;


use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\TaskId;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class DeleteTaskUseCase
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * DeleteTaskUseCase constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function __invoke(int $id): JsonResponse
    {
        $taskId = new TaskId($id);
        $userId = new UserId(Auth::id());
        $taskStatus = new TaskStatus(Task::STATUS_DISABLED);
        $this->taskRepository->updateStatus($taskId, $userId, $taskStatus);
        return response()->json([
            'result' => true,
        ]);
    }
}
