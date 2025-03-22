<?php



namespace App\Repositories;


use App\Repositories\Interfaces\CourseCategoryRepositoryInterface;

class CourseCategoryRepository extends BaseRepository implements CourseCategoryRepositoryInterface {
    public function __construct(\App\Models\CourseCategory   $model) {
        parent::__construct($model);
    }
}
