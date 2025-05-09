<?php

namespace App\Contracts\Services\Guest;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{

    /**
     * @param int $itemPerPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $itemPerPage = 5, array $columns = ['*']): LengthAwarePaginator;

    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;

    /**
     * @param string $id
     * @return Task|null
     */
    public function find(string $id): ?Task;

    /**
     * @param string $column
     * @param string $value
     * @return Task|null
     */
    public function findBy(string $column, string $value): ?Task;

    /**
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
