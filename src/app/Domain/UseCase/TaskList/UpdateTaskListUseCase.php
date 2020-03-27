<?php
declare(strict_types=1);

namespace App\Domain\UseCase\TaskList;


use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListSort;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\UpdateTaskListRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class UpdateTaskListUseCase
{
    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    /**
     * UpdateTaskListUseCase constructor.
     *
     * @param TaskListRepositoryInterface $taskListRepository
     */
    public function __construct(TaskListRepositoryInterface $taskListRepository)
    {
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @param int $id
     * @param UpdateTaskListRequest $request
     * @return JsonResponse
     */
    public function __invoke(int $id, UpdateTaskListRequest $request)
    {
        $userId = new UserId(Auth::id());
        $taskListId = new TaskListId((int) $id);
        $taskListName = new TaskListName((string) $request->get('name'));
        $taskListSort = new TaskListSort((int) $request->get('sort', 0));

        $taskList = DB::transaction(function () use ($userId, $taskListId, $taskListName, $taskListSort) {
            return $this->taskListRepository->updateById($taskListId, $userId, $taskListName, $taskListSort);
        });

        return response()->json($taskList);
    }
}
