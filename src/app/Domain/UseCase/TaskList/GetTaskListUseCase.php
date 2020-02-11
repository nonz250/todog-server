<?php


namespace App\Domain\UseCase\TaskList;


use App\Domain\Collection\TaskStatusCollection;
use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\ValueObject\QueryParamTaskStatus;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\GetTaskListRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class GetTaskListUseCase
{
    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    /**
     * GetTaskListUseCase constructor.
     *
     * @param TaskListRepositoryInterface $taskListRepository
     */
    public function __construct(TaskListRepositoryInterface $taskListRepository)
    {
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @param GetTaskListRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function __invoke(GetTaskListRequest $request): JsonResponse
    {
        $userId = new UserId(Auth::id());
        $queryStatus = new QueryParamTaskStatus($request->get('status'));

        if ($queryStatus->isEnable()) {
            $taskStatuses = new TaskStatusCollection([
                new TaskStatus(Task::STATUS_DEFAULT),
                new TaskStatus(Task::STATUS_COMPLETED),
            ]);
            $taskLists = $this->taskListRepository->findByTaskStatuses($userId, $taskStatuses)->get();
        } elseif ($queryStatus->isDisabled()) {
            $taskLists = $this->taskListRepository->findAll($userId)->get();
        } else {
            $taskLists = $this->taskListRepository->findAll($userId)->get();
        }

        $results = [];

        foreach ($taskLists as $taskList) {
            $results[] = [
                'id' => $taskList->id,
                'name' => $taskList->name,
                'tasks' => $taskList->tasks,
            ];
        }

        return response()->json($results);
    }
}
