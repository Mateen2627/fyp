<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLmsWorkflowTables extends Migration
{
    public function up()
    {
        Schema::create('department_staff', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('user_id');
            $table->boolean('is_hod')->default(false);
            $table->boolean('is_coordinator')->default(false);
            $table->timestamps();
            $table->unique(['department_id', 'user_id']);
            $table->foreign('department_id')->references('id')->on('class_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedInteger('subject_id')->nullable()->index();
            $table->boolean('teaching_initialized')->default(false);
        });
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->unsignedInteger('section_id')->nullable()->index();
            $table->dropUnique('teacher_assignments_course_teacher_class_unique');
            $table->unique(['course_id', 'teacher_id', 'my_class_id', 'section_id'], 'teaching_course_class_section_unique');
        });
        foreach (['assignments', 'quizzes'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->unsignedInteger('section_id')->nullable()->index();
            });
        }
        Schema::table('assignments', function (Blueprint $table) {
            $table->unsignedInteger('total_marks')->default(100);
        });
        Schema::table('time_table_records', function (Blueprint $table) {
            $table->unsignedInteger('section_id')->nullable()->index();
        });
        Schema::table('student_attendance_records', function (Blueprint $table) {
            $table->unsignedInteger('time_table_id')->nullable();
            $table->unsignedInteger('course_id')->nullable()->index();
            $table->unsignedInteger('my_class_id')->nullable()->index();
            $table->unsignedInteger('section_id')->nullable()->index();
            $table->unique(['user_id', 'time_table_id', 'attendance_date'], 'attendance_student_lesson_date_unique');
        });
        Schema::create('attendance_check_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('time_table_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id');
            $table->date('attendance_date');
            $table->string('status', 20)->default('pending');
            $table->string('image_path')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'time_table_id', 'attendance_date'], 'check_in_student_lesson_date_unique');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Link the existing subject and course catalogs without changing their IDs.
        foreach (DB::table('courses')->get() as $course) {
            $subject = DB::table('subjects')->where('my_class_id', $course->my_class_id)
                ->where(function ($query) use ($course) {
                    $query->where('slug', $course->code)->orWhere('name', $course->title);
                })->first();
            if ($subject) {
                DB::table('courses')->where('id', $course->id)->update(['subject_id' => $subject->id]);
            }
        }
        foreach (DB::table('student_records')->get() as $student) {
            DB::table('student_attendance_records')->where('user_id', $student->user_id)->update([
                'my_class_id' => $student->my_class_id, 'section_id' => $student->section_id,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('attendance_check_ins');
        Schema::table('student_attendance_records', function (Blueprint $table) {
            $table->dropUnique('attendance_student_lesson_date_unique');
            $table->dropColumn(['time_table_id', 'course_id', 'my_class_id', 'section_id']);
        });
        Schema::table('time_table_records', function (Blueprint $table) {
            $table->dropColumn('section_id');
        });
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('total_marks');
        });
        foreach (['assignments', 'quizzes'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropColumn('section_id');
            });
        }
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropUnique('teaching_course_class_section_unique');
            $table->dropColumn('section_id');
            $table->unique(['course_id', 'teacher_id', 'my_class_id'], 'teacher_assignments_course_teacher_class_unique');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['subject_id', 'teaching_initialized']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('department_staff');
    }
}
