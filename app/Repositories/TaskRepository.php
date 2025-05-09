<?php

namespace App\Repositories;

use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

/**
 *
 */
class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        $this->setModel($model);
    }

    public function queryBuilder(): Builder
    {
        $query = $this->model->newQuery();
        auth()->check() ? $query->where('user_id', auth()->id()) : $query->where('ip_address', Request::ip());
        return $query->latest();
    }

}
