<?php


namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        $model= $this->model->create($data);
        // if request has file
        if (request()->hasFile('image')) {
            Storage::uploadFile(request()->file('image'), $model);
        }

        return $model;
    }

    public function update($id, array $data) {
        $model = $this->find($id);
        if ($model) {
            $model->update($data);
            // if request has file
            if (request()->hasFile('image')) {
                Storage::uploadFile(request()->file('image'), $model);
            }

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
