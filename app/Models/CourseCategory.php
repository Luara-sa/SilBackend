<?php

namespace App\Models;

use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CourseCategory extends Model {
    use HasFactory, HasTranslations, GeneralTrait;

    protected $fillable = ['name','color'];
    public $translatable = ['name'];

}
