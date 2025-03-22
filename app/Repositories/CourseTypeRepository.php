<?php


namespace App\Repositories;

use App\Models\CourseType;
use App\Repositories\Interfaces\CourseTypeRepositoryInterface;

class CourseTypeRepository extends BaseRepository implements CourseTypeRepositoryInterface {
    public function __construct(CourseType $model) {
        parent::__construct($model);
    }
}
