<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskListsTableSort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->integer('sort')->index()->after('name')->comment('ソート');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
}
