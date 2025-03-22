<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        DB::table('course_types')->truncate();
        DB::table('course_categories')->truncate();
        DB::table('courses')->truncate();
        Schema::enableForeignKeyConstraints();

        // Insert Course Types
        $courseTypes = [
            ['name' => json_encode(['en' => 'Synchronous', 'ar' => 'متزامن']), 'is_active' => true],
            ['name' => json_encode(['en' => 'Asynchronous', 'ar' => 'غير متزامن']), 'is_active' => true],
        ];
        DB::table('course_types')->insert($courseTypes);

        // Insert Course Categories
        $courseCategories = [
            ['name' => json_encode(['en' => 'Technical', 'ar' => 'تقني']), 'is_active' => true],
            ['name' => json_encode(['en' => 'Business', 'ar' => 'اعمال']), 'is_active' => true],
            ['name' => json_encode(['en' => 'Health', 'ar' => 'صحة']), 'is_active' => true],
        ];
        DB::table('course_categories')->insert($courseCategories);

        // Fetch inserted type and category IDs
        $typeId = DB::table('course_types')->first()->id;
        $categoryId = DB::table('course_categories')->first()->id;

        // Insert Courses
        $courses = [
            [
                'name' => json_encode(['en' => 'Web Development', 'ar' => 'تطوير الويب']),
                'description' => json_encode(['en' => 'Learn how to develop websites', 'ar' => 'تعلم كيفية تطوير مواقع الويب']),
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
                'type_id' => $typeId,
                'has_sections' => true,
                'gender' => 'Mixed',
                'course_category_id' => $categoryId,
                'is_organizational' => false,
                'course_mode' => 'Online',
                'course_format' => 'Structured',
                'payment_required' => true,
                'is_active' => true,
            ],
        ];
        DB::table('courses')->insert($courses);
    }
}
