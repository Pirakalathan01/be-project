<?php

namespace App\Providers;

use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\Guest\TaskServiceInterface as GuestTaskServiceInterface;
use App\Contracts\Services\TaskServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Services\Guest\TaskService as GuestTaskService;
use App\Services\TaskService;
use App\Services\UserService;

class RepositoryServiceProvider extends AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(GuestTaskServiceInterface::class, GuestTaskService::class);

        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

}
