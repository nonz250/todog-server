<?php


namespace App\Domain\UseCase\TaskList;


use App\Domain\Repository\TaskListRepositoryInterface;
use Illuminate\Http\JsonResponse;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $taskLists = $this->taskListRepository->findAll();
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
