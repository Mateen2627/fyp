<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMyClassIdToAssignmentsAndQuizzes extends Migration
{
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'my_class_id')) {
                $table->unsignedInteger('my_class_id')->nullable()->after('teacher_id');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'my_class_id')) {
                $table->unsignedInteger('my_class_id')->nullable()->after('teacher_id');
            }
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'my_class_id')) {
                $table->dropColumn('my_class_id');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'my_class_id')) {
                $table->dropColumn('my_class_id');
            }
        });
    }
}
