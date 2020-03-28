<?php
declare(strict_types=1);

namespace App\Domain\UseCase\Task;


use App\Domain\Collection\TaskIdCollection;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\TaskId;
use App\Domain\ValueObject\UserId;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ArchiveTaskUseCase
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * ArchiveTaskUseCase constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param string $ids
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(string $ids): JsonResponse
    {
        $userId = new UserId(Auth::id());
        $ids = explode(',', $ids);
        if (count($ids) === 0) {
            throw new ValidationException('アーカイブするタスクが選択されていません。');
        }
        $taskIdCollection = new TaskIdCollection();
        foreach ($ids as $taskId) {
            $taskIdCollection->push(new TaskId((int)$taskId));
        }

        $tasks = $this->taskRepository->deleteByIdCollection($taskIdCollection, $userId);

        return response()->json([
            'result' => true,
            'task_ids' => $tasks->toArray(),
        ]);
    }
}
