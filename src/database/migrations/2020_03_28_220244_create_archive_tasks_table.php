<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchiveTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_tasks', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('task_list_id')->index()->comment('タスクリストID');
            $table->unsignedBigInteger('user_id')->index()->comment('ユーザーID');
            $table->string('name', 255)->comment('タスク名');
            $table->timestamp('notification_start_date')->nullable()->comment('通知開始日');
            $table->timestamp('limit_date')->nullable()->comment('期限');
            $table->bigInteger('status')->default(0)->index()->comment('状態');
            $table->timestamps();

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
        Schema::dropIfExists('archive_tasks');
    }
}
