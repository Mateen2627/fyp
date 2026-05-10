<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectAssessmentMarksTable extends Migration
{
    public function up()
    {
        Schema::create('subject_assessment_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->string('academic_year');
            $table->decimal('mid_marks', 5, 2)->nullable();
            $table->decimal('final_marks', 5, 2)->nullable();
            $table->decimal('total_marks', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'academic_year'], 'student_subject_year_unique');
            $table->index(['teacher_id', 'subject_id', 'my_class_id', 'section_id'], 'subject_marks_teacher_filter_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_assessment_marks');
    }
}
