<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 *
 */
interface BaseRepositoryInterface
{
    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @param int $itemPerPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $itemPerPage = 5, array $columns = ['*']): LengthAwarePaginator;

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;

    /**
     * @param string $id
     * @return mixed
     */
    public function find(string $id): mixed;

    /**
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public function findBy(string $column, string $value): mixed;

    /**
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool;

    /**
     * @param string $column
     * @param string $value
     * @param array $data
     * @return bool
     */
    public function updateBy(string $column, string $value, array $data): bool;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

}
