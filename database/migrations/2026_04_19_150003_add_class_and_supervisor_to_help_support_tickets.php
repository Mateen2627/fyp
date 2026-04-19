<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassAndSupervisorToHelpSupportTickets extends Migration
{
    public function up()
    {
        Schema::table('student_help_support_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('student_help_support_tickets', 'my_class_id')) {
                $table->unsignedInteger('my_class_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('student_help_support_tickets', 'supervisor_id')) {
                $table->unsignedInteger('supervisor_id')->nullable()->after('my_class_id');
            }
        });
    }

    public function down()
    {
        Schema::table('student_help_support_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('student_help_support_tickets', 'supervisor_id')) {
                $table->dropColumn('supervisor_id');
            }
            if (Schema::hasColumn('student_help_support_tickets', 'my_class_id')) {
                $table->dropColumn('my_class_id');
            }
        });
    }
}
