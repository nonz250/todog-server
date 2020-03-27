<?php


namespace App\Domain\UseCase\TaskList;


use App\Domain\Collection\TaskListStatusCollection;
use App\Domain\Collection\TaskStatusCollection;
use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\ValueObject\QueryParamTaskStatus;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\GetTaskListRequest;
use App\Models\Task;
use App\Models\TaskList;
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
            $taskStatusCollection = new TaskStatusCollection([
                new TaskStatus(Task::STATUS_DEFAULT),
                new TaskStatus(Task::STATUS_COMPLETED),
            ]);

            $taskListStatusCollection = new TaskListStatusCollection([
                new TaskListStatus(TaskList::STATUS_ENABLED),
            ]);

            $taskLists = $this->taskListRepository
                ->findByTaskStatuses($userId, $taskListStatusCollection, $taskStatusCollection)
                ->get();
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
                'sort' => $taskList->sort,
                'tasks' => $taskList->tasks,
            ];
        }

        return response()->json($results);
    }
}
