<?php


namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface {
    protected Model $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function all() {
        return $this->model->all();
    }

    public function find($id) {
        return $this->model->find($id);
    }

    public function create() {
        return $this->model->create();
    }


    public function store(array $data) {
        return $this->model->store($data);
    }

    public function update($id, array $data) {
        $model = $this->find($id);
        if ($model) {
            $model->update($data);
            return $model;
        }
        return null;
    }

    public function delete($id) {
        $model = $this->find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }
}
