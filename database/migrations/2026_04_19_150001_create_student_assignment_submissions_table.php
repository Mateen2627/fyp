<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAssignmentSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('student_assignment_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id');
            $table->unsignedInteger('student_id');
            $table->text('submission_text')->nullable();
            $table->string('attachment_path')->nullable();
            $table->decimal('marks', 5, 2)->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id'], 'assignment_student_unique_submission');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_assignment_submissions');
    }
}
