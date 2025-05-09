<?php

namespace App\Services;

use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\TaskServiceInterface;
use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class TaskService implements TaskServiceInterface
{

    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param int $itemPerPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $itemPerPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->taskRepository->paginate($itemPerPage, $columns);
    }


    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->taskRepository->all($columns);
    }

    /**
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        $data['status'] = Status::Pending;
        $data['ip_address'] = null;
        $data['user_id'] = auth()->user()->id;
        return $this->taskRepository->create($data);
    }

    /**
     * @param string $id
     * @return Task|null
     */
    public function find(string $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    /**
     * @param string $column
     * @param string $value
     * @return Task|null
     */
    public function findBy(string $column, string $value): ?Task
    {
        return $this->taskRepository->findBy($column, $value);
    }

    /**
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        return $this->taskRepository->update($id, $data);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->taskRepository->delete($id);
    }
}
