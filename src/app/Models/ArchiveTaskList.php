<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveTaskList extends Model
{
    protected $table = 'archive_task_lists';
    protected $fillable = ['id', 'user_id', 'name', 'sort', 'status'];
}
