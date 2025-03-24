<?php
namespace App\Models;

use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Course extends Model {
    use HasFactory, HasTranslations, GeneralTrait;

    protected $fillable = [
        'name', 'description', 'start_date', 'end_date', 'status','price',
        'type_id', 'has_sections', 'gender', 'course_category_id',
        'is_organizational', 'course_mode', 'course_format', 'payment_required'
    ];
    public $translatable = ['name','description'];


    public function type() {
        return $this->belongsTo(CourseType::class);
    }


    public function category() {
        return $this->belongsTo(CourseCategory::class);
    }
}
