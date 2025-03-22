<?php


namespace App\Http\Controllers\Api\CourseType;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseTypeRequest;
use App\Http\Resources\CourseType;
use App\Repositories\Interfaces\CourseTypeRepositoryInterface;

class CourseTypeController extends Controller
{

    protected $courseTypeRepository;

    public function __construct(CourseTypeRepositoryInterface $courseTypeRepository) {
        $this->courseTypeRepository = $courseTypeRepository;
    }

    public function index() {
       $courseTypes = $this->courseTypeRepository->all();

        return response()->jsonResponse(
            true,
            __('messages.data_retrieved'),
            CourseType::collection($courseTypes),
            200
        );
    }


    public function show($id) {
        $courseType = $this->courseTypeRepository->find($id);

        if (!$courseType) {
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
            $courseType,
            200
        );
    }

    public function store(CourseTypeRequest $request) {
        $data = $this->courseTypeRepository->store($request->validated());

        return response()->jsonResponse(
            true,
            __('messages.data_stored'),
            $data,
            201
        );
    }

    public function update(CourseTypeRequest $request, $id) {
        $data = $this->courseTypeRepository->update($request->validated(), $id);

        return response()->jsonResponse(
            true,
            __('messages.data_updated'),
            $data,
            200
        );
    }


    public function destroy($id) {
        $this->courseTypeRepository->destroy($id);

        return response()->jsonResponse(
            true,
            __('messages.data_deleted'),
            null,
            200
        );
    }



}


