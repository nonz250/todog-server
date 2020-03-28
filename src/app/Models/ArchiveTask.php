<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveTask extends Model
{
    protected $table = 'archive_tasks';
    protected $fillable = [
        'id',
        'task_list_id',
        'user_id',
        'name',
        'limit_date',
        'notification_start_date',
        'status'
    ];
}
