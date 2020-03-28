<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchiveTaskListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_task_lists', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('user_id')->index()->comment('ユーザーID');
            $table->string('name', 255)->comment('リスト名');
            $table->integer('sort')->index()->comment('ソート');
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
        Schema::dropIfExists('archive_task_lists');
    }
}
