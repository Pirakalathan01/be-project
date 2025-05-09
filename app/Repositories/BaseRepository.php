<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 *
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model $model
     */
    protected Model $model;

    /**
     * @param Model $model
     * @return void
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return Builder
     */
    public function queryBuilder(): Builder
    {
        return $this->model->query();
    }

    /**
     * @return mixed
     */
    protected function query(): Builder
    {
        $builder = $this->queryBuilder();
        $builder = $this->model->filterable ? $this->filters($builder) : $builder;
        return $this->model->relationable ? $this->relations($builder) : $builder;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function relations(Builder $builder): Builder
    {
        foreach ($this->model->relationable as $key => $columns) {
            $builder->with([$key => function ($q) use ($columns) {
                $q->select($columns);
            }]);
        }
        return $builder;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function filters(Builder $builder): Builder
    {
        foreach (request()->all() as $key => $value) {
            if (is_null($value) || !array_key_exists($key, $this->model->filterable)) continue;
            $operator = $this->model->filterable[$key];
            $builder->where($key, $operator, $value . ($operator == 'like' ? '%' : null));
        }
        return $builder;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function find(string $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public function findBy(string $column, string $value): mixed
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->select($columns)->get();
    }

    /**
     * @param int $itemPerPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $itemPerPage = 5, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->select($columns)->paginate(5);
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $column
     * @param string $direction
     * @return Collection
     */
    public function take(int $limit = 5, array $columns = ['*'], string $column = 'created_at', string $direction = 'desc'): Collection
    {
        return $this->query()->select($columns)->orderBy($column, $direction)->limit($limit)->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        return $this->find($id)->fill($data)->save();
    }

    /**
     * @param string $column
     * @param string $value
     * @param array $data
     * @return bool
     */
    public function updateBy(string $column, string $value, array $data): bool
    {
        return $this->model->where($column, $value)->update($data);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->find($id)->delete();
    }
}
