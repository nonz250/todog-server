<?php
declare(strict_types=1);

namespace App\Domain\UseCase\TaskList;


use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListSort;
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
        $taskListName = new TaskListName((string) $request->get('name'));
        $taskListSort = new TaskListSort((int) $request->get('sort', 0));
        $taskListStatus = new TaskListStatus(TaskList::STATUS_ENABLED);

        $taskList = $this->taskListRepository->saveTaskList($userId, $taskListName, $taskListSort, $taskListStatus);

        return response()->json([
            'id' => $taskList->getAttribute('id'),
            'name' => $taskList->getAttribute('name'),
            'sort' => $taskList->getAttribute('sort'),
        ]);
    }
}
