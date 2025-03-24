<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'name.*' => 'required|string', // Add 'name.*' rule
            'description' => 'required',
            'description.*' => 'required|string', // Add 'description.*' rule
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_active' => 'sometimes|boolean',
            'price' => 'required|numeric',
            'type_id' => 'required|exists:course_types,id',
            'has_sections' => 'required|boolean',
            'gender' => 'required',
            'course_category_id' => 'required|exists:course_categories,id',
            'is_organizational' => 'required|boolean',
            'course_mode' => 'required|in:Online,In-Person,Hybrid',
            'course_format' => 'required|in:Structured,Unstructured',
            'payment_required' => 'required|boolean',
        ];
    }
}
