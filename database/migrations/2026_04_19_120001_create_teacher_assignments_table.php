<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('assigned_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'teacher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_assignments');
    }
}
