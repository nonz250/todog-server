<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_list_id')->index()->comment('タスクリストID');
            $table->unsignedBigInteger('user_id')->index()->comment('ユーザーID');
            $table->string('name', 255)->comment('タスク名');
            $table->bigInteger('status')->default(0)->index()->comment('状態');
            $table->timestamps();

            $table->foreign('task_list_id')->references('id')->on('tasks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
