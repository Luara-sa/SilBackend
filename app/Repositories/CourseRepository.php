<?php


namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseType;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface {
    public function __construct(Course $model) {
        parent::__construct($model);
    }

    // override the create method

    public function create() {
        return [
            'course_types' => CourseType::all(),
            'course_categories' => CourseCategory::all(),
        ];
    }


}
