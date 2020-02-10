<?php


namespace App\Domain\UseCase\Task;


use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\CreateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateTaskUseCase
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param CreateTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function __invoke(CreateTaskRequest $request): JsonResponse
    {
        $userId = new UserId(Auth::id());
        $taskListId = new TaskListId($request->get('task_list_id'));
        $taskName = new TaskName($request->get('name'));
        $taskStatus = new TaskStatus(Task::STATUS_DEFAULT);

        $task = $this->taskRepository->saveTask($userId, $taskListId, $taskName, $taskStatus);

        return response()->json([
            'id' => $task->getAttribute('id'),
            'task_list_id' => $task->getAttribute('task_list_id'),
            'name' => $task->getAttribute('name'),
        ]);
    }
}
