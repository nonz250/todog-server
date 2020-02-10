<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/task_list', '\App\Domain\UseCase\TaskList\GetTaskListUseCase');
    Route::post('/task_list', '\App\Domain\UseCase\TaskList\CreateTaskListUseCase');

    Route::post('/task', '\App\Domain\UseCase\Task\CreateTaskUseCase');
});
