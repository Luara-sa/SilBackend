<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('type_id')->constrained('course_types');
            $table->boolean('has_sections');
            $table->string('gender');
            $table->foreignId('course_category_id')->constrained('course_categories');
            $table->boolean('is_organizational');
            $table->enum('course_mode', ['Online', 'In-Person', 'Hybrid'])->nullable();
            $table->enum('course_format', ['Structured', 'Unstructured'])->nullable();
            $table->boolean('payment_required'); // true for paid courses
            $table->boolean('is_active')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
