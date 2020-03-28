<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTaskTableForeignTaskListId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_task_list_id_foreign');
            $table->foreign('task_list_id')->references('id')->on('task_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_task_list_id_foreign');
            $table->foreign('task_list_id')->references('id')->on('tasks');
        });
    }
}
