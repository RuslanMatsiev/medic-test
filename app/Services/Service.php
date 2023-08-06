<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

abstract class Service
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model->query();
    }

    /**
     * Method for creating a new object for the model
     *
     * @param array $data
     * @return Builder|Model
     */
    public function create(array $data): Builder|Model
    {
        return $this->model->create($data);
    }
}
