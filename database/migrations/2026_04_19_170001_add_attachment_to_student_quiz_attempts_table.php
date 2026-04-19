<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentToStudentQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::table('student_quiz_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('student_quiz_attempts', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('answers');
            }
        });
    }

    public function down()
    {
        Schema::table('student_quiz_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('student_quiz_attempts', 'attachment_path')) {
                $table->dropColumn('attachment_path');
            }
        });
    }
}
