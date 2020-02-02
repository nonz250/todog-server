<?php

namespace App\Providers;

use App\Domain\Repository\TaskListRepository;
use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\Repository\TaskRepository;
use App\Domain\Repository\TaskRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(TaskListRepositoryInterface::class, TaskListRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
