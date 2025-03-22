<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;

use App\Http\Requests\CourseRequest;

use App\Http\Resources\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository) {
        $this->courseRepository = $courseRepository;
    }

    public function index() {
       $courses = $this->courseRepository->all();

        return response()->jsonResponse(
            true,
            __('messages.data_retrieved'),
            Course::collection($courses),
            200
        );
    }

    public function show($id) {
        $course = $this->courseRepository->find($id);

        if (!$course) {
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
            $course,
            200
        );
    }

    public function store(CourseRequest $request) {
        $data = $this->courseRepository->store($request->validated());

        return response()->jsonResponse(
            true,
            __('messages.data_stored'),
            $data,
            201
        );
    }

    public function update(CourseRequest $request, $id) {
        $data = $this->courseRepository->update($request->validated(), $id);

        return response()->jsonResponse(
            true,
            __('messages.data_updated'),
            $data,
            200
        );
    }

    public function destroy($id) {
        $data = $this->courseRepository->destroy($id);

        return response()->jsonResponse(
            true,
            __('messages.data_deleted'),
            $data,
            200
        );
    }


}
