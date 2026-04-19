<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('student_quiz_attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_id');
            $table->unsignedInteger('student_id');
            $table->text('answers')->nullable();
            $table->decimal('marks', 5, 2)->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['quiz_id', 'student_id'], 'quiz_student_unique_attempt');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_quiz_attempts');
    }
}
