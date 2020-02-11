<?php

namespace App\Models;

use App\Domain\Collection\TaskStatusCollection;
use App\Domain\ValueObject\TaskListName;
use App\Domain\ValueObject\TaskListStatus;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $table = 'task_lists';
    protected $fillable = ['user_id', 'name', 'status'];
    protected $guarded = ['id'];

    /** @var int 有効 */
    public const STATUS_ENABLED = 0;
    /** @var int 無効 */
    public const STATUS_DISABLED = 1;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @param UserId $userId
     * @param TaskListName $taskListName
     * @param TaskListStatus $taskListStatus
     * @return bool
     */
    public function saveTaskList(UserId $userId, TaskListName $taskListName, TaskListStatus $taskListStatus): bool
    {
        return $this->fill([
            'user_id' => $userId->toInt(),
            'name' => (string) $taskListName,
            'status' => $taskListStatus->toInt(),
        ])->save();
    }

    /**
     * @param UserId $userId
     * @return Builder
     */
    public static function findByUserId(UserId $userId): Builder
    {
        return self::where('user_id', $userId->toInt());
    }
}
