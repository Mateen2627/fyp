<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMyClassIdToTeacherAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_assignments', 'my_class_id')) {
                $table->unsignedInteger('my_class_id')->nullable()->after('course_id');
            }
        });
    }

    public function down()
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_assignments', 'my_class_id')) {
                $table->dropColumn('my_class_id');
            }
        });
    }
}
