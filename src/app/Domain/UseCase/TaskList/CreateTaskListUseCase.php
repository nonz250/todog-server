<?php


namespace App\Domain\UseCase\TaskList;


use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\CreateTaskListRequest;
use App\Models\TaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class CreateTaskListUseCase
{
    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    /**
     * CreateTaskListUseCase constructor.
     *
     * @param TaskListRepositoryInterface $taskListRepository
     */
    public function __construct(TaskListRepositoryInterface $taskListRepository)
    {
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @param CreateTaskListRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function __invoke(CreateTaskListRequest $request): JsonResponse
    {
        $userId = new UserId(Auth::id());
        $taskListName = new TaskListName($request->get('name'));
        $taskListStatus = new TaskListStatus(TaskList::STATUS_ENABLED);

        $taskList = $this->taskListRepository->saveTaskList($userId, $taskListName, $taskListStatus);

        return response()->json([
            'id' => $taskList->getAttribute('id'),
            'name' => $taskList->getAttribute('name'),
        ]);
    }
}
