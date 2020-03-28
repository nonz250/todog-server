<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ArchiveTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        $taskLists = \App\Models\TaskList::all();
        $tasks = \App\Models\Task::all();

        $archiveTasks = [];
        foreach ($tasks as $task) {
            if ((int) $task->getAttribute('status') === \App\Models\Task::STATUS_DISABLED) {
                $archiveTasks[] = $task;
                $task->delete();
            }
        }

        $archiveTaskLists = [];
        foreach ($taskLists as $taskList) {
            if ((int) $taskList->getAttribute('status') === \App\Models\TaskList::STATUS_DISABLED) {
                $archiveTaskLists[] = $taskList;
                $taskList->delete();
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($archiveTaskLists, $archiveTasks) {
            foreach ($archiveTaskLists as $archiveTaskList) {
                $archiveTaskListModel = new \App\Models\ArchiveTaskList();
                $archiveTaskListModel = $archiveTaskListModel->fill($archiveTaskList->toArray());
                $archiveTaskListModel->setAttribute('status', \App\Models\TaskList::STATUS_ENABLED);

                if ($archiveTaskListModel->save() === false) {
                    throw new Exception('データ移行に失敗しました。');
                }
            }

            foreach ($archiveTasks as $archiveTask) {
                $archiveTaskModel = new \App\Models\ArchiveTask();
                $archiveTaskModel = $archiveTaskModel->fill($archiveTask->toArray());
                $archiveTaskModel->setAttribute('status', \App\Models\Task::STATUS_DEFAULT);

                if ($archiveTaskModel->save() === false) {
                    throw new Exception('データ移行に失敗しました。');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
