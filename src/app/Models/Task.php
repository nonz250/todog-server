<?php
declare(strict_types=1);

namespace App\Models;

use App\Domain\Collection\UserIdCollection;
use App\Domain\ValueObject\TaskId;
use App\Domain\ValueObject\TaskLimitDate;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\TaskName;
use App\Domain\ValueObject\TaskStatus;
use App\Domain\ValueObject\UserId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['task_list_id', 'user_id', 'name', 'limit_date', 'status'];
    protected $guarded = ['id'];

    /** @var int デフォルト */
    public const STATUS_DEFAULT = 0;
    /** @var int 完了 */
    public const STATUS_COMPLETED = 1;
    /** @var int 無効 */
    public const STATUS_DISABLED = 2;

    public function token()
    {
        return $this->hasMany(FcmToken::class, 'user_id', 'user_id');
    }

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @param TaskName $taskName
     * @param TaskStatus $taskStatus
     * @return bool
     */
    public function saveTask(TaskListId $taskListId, UserId $userId, TaskName $taskName, TaskStatus $taskStatus): bool
    {
        return $this->fill([
            'task_list_id' => $taskListId->toInt(),
            'user_id' => $userId->toInt(),
            'name' => (string) $taskName,
            'status' => $taskStatus->toInt(),
        ])->save();
    }

    /**
     * @param TaskId $taskId
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @param TaskName $taskName
     * @param TaskLimitDate $taskLimitDate
     * @param TaskStatus $taskStatus
     * @return bool
     */
    public static function updateTask(
        TaskId $taskId,
        TaskListId $taskListId,
        UserId $userId,
        TaskName $taskName,
        TaskLimitDate $taskLimitDate,
        TaskStatus $taskStatus
    ): bool {
        return self::firstOrNew([
            'id' => $taskId->toInt(),
            'user_id' => $userId->toInt(),
        ])->fill([
            'task_list_id' => $taskListId->toInt(),
            'name' => (string) $taskName,
            'limit_date' => $taskLimitDate->format('Y/m/d'),
            'status' => $taskStatus->toInt(),
        ])->save();
    }

    /**
     * @param TaskId $taskId
     * @return Builder
     */
    public static function findById(TaskId $taskId): Builder
    {
        return self::where('id', $taskId->toInt());
    }

    /**
     * @param UserIdCollection $userIdCollection
     * @return Builder
     */
    public static function findByIds(UserIdCollection $userIdCollection): Builder
    {
        return self::whereIn('user_id', $userIdCollection->toArray());
    }

    /**
     * @param TaskId $taskId
     * @param UserId $userId
     * @param TaskStatus $taskStatus
     * @return bool
     */
    public static function updateStatusById(TaskId $taskId, UserId $userId, TaskStatus $taskStatus): bool
    {
        return self::firstOrNew([
            'id' => $taskId->toInt(),
            'user_id' => $userId->toInt(),
        ])->fill([
            'status' => $taskStatus->toInt(),
        ])->save();
    }

    /**
     * @param TaskListId $taskListId
     * @param UserId $userId
     * @return Builder
     */
    public static function findByTaskListId(TaskListId $taskListId, UserId $userId): Builder
    {
        return self::where('task_list_id', $taskListId->toInt())
            ->where('user_id', $userId->toInt());
    }
}
