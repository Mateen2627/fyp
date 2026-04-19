<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModeToStudentAttendanceRecords extends Migration
{
    public function up()
    {
        Schema::table('student_attendance_records', function (Blueprint $table) {
            if (!Schema::hasColumn('student_attendance_records', 'mode')) {
                $table->string('mode', 20)->default('manual')->after('status');
            }
            if (!Schema::hasColumn('student_attendance_records', 'facial_verified')) {
                $table->boolean('facial_verified')->default(0)->after('mode');
            }
        });
    }

    public function down()
    {
        Schema::table('student_attendance_records', function (Blueprint $table) {
            if (Schema::hasColumn('student_attendance_records', 'facial_verified')) {
                $table->dropColumn('facial_verified');
            }
            if (Schema::hasColumn('student_attendance_records', 'mode')) {
                $table->dropColumn('mode');
            }
        });
    }
}
