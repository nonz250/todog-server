<?php

namespace App\Models;

use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['task_list_id', 'user_id', 'name', 'status'];
    protected $guarded = ['id'];

    /** @var int デフォルト */
    public const STATUS_DEFAULT = 0;
    /** @var int 完了 */
    public const STATUS_COMPLETED = 1;
    /** @var int 無効 */
    public const STATUS_DISABLED = 2;

    /**
     * @param UserId $userId
     * @param TaskListId $taskListId
     * @param TaskName $taskName
     * @param TaskStatus $taskStatus
     * @return bool
     */
    public function saveTask(UserId $userId, TaskListId $taskListId, TaskName $taskName, TaskStatus $taskStatus): bool
    {
        return $this->fill([
            'task_list_id' => $taskListId->toInt(),
            'user_id' => $userId->toInt(),
            'name' => (string)$taskName,
            'status' => $taskStatus->toInt(),
        ])->save();
    }
}
