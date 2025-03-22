<?php


namespace App\Http\Controllers\Api\CourseCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseCategoryRequest;
use App\Http\Resources\CourseCategory;
use App\Repositories\Interfaces\CourseCategoryRepositoryInterface;

class CourseCategoryController extends Controller
{

    protected $courseCategoryRepository;

    public function __construct(CourseCategoryRepositoryInterface $courseCategoryRepository) {
        $this->courseCategoryRepository = $courseCategoryRepository;
    }

    public function index() {
       $courseCategories = $this->courseCategoryRepository->all();

        return response()->jsonResponse(
            true,
            __('messages.data_retrieved'),
            CourseCategory::collection($courseCategories),
            200
        );
    }


    public function show($id) {
        $courseCategory = $this->courseCategoryRepository->find($id);

        if (!$courseCategory) {
            return response()->jsonResponse(
                false,
                __('messages.data_not_found'),
                null,
                404
            );
        }

        return response()->jsonResponse(
            true,
            __('messages.data_retrieved'),
            $courseCategory,
            200
        );
    }

    public function store(CourseCategoryRequest $request) {
        $data = $this->courseCategoryRepository->store($request->validated());

        return response()->jsonResponse(
            true,
            __('messages.data_stored'),
            $data,
            201
        );
    }

    public function update(CourseCategoryRequest $request, $id) {
        $data = $this->courseCategoryRepository->update($request->validated(), $id);

        return response()->jsonResponse(
            true,
            __('messages.data_updated'),
            $data,
            200
        );
    }

    public function destroy($id) {
        $this->courseCategoryRepository->delete($id);

        return response()->jsonResponse(
            true,
            __('messages.data_deleted'),
            null,
            200
        );
    }



}
