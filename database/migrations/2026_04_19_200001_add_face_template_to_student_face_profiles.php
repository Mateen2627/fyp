<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaceTemplateToStudentFaceProfiles extends Migration
{
    public function up()
    {
        Schema::table('student_face_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('student_face_profiles', 'face_template')) {
                $table->longText('face_template')->nullable()->after('face_token');
            }
        });
    }

    public function down()
    {
        Schema::table('student_face_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('student_face_profiles', 'face_template')) {
                $table->dropColumn('face_template');
            }
        });
    }
}
