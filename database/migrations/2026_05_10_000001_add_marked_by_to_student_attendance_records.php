<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkedByToStudentAttendanceRecords extends Migration
{
    public function up()
    {
        Schema::table('student_attendance_records', function (Blueprint $table) {
            if (!Schema::hasColumn('student_attendance_records', 'marked_by')) {
                $table->unsignedInteger('marked_by')->nullable()->after('remarks');
            }
        });
    }

    public function down()
    {
        Schema::table('student_attendance_records', function (Blueprint $table) {
            if (Schema::hasColumn('student_attendance_records', 'marked_by')) {
                $table->dropColumn('marked_by');
            }
        });
    }
}
