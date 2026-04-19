<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeacherAssignmentsUniqueIndex extends Migration
{
    public function up()
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropUnique('teacher_assignments_course_id_teacher_id_unique');
            $table->unique(['course_id', 'teacher_id', 'my_class_id'], 'teacher_assignments_course_teacher_class_unique');
        });
    }

    public function down()
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropUnique('teacher_assignments_course_teacher_class_unique');
            $table->unique(['course_id', 'teacher_id']);
        });
    }
}
