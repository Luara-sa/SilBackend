<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Course extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'course_category_id' => $this->course_category_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'has_sections' => $this->has_sections,
            'course_type' => new CourseType($this->type),
            'course_category' => new CourseCategory($this->category),
            'is_organizational' => $this->is_organizational,
            'course_mode' => $this->course_mode,
            'course_format' => $this->course_format,
            'payment_required' => $this->payment_required,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
