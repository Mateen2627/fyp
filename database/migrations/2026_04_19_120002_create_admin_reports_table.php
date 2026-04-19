<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminReportsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('category', 100)->nullable();
            $table->longText('content');
            $table->string('status', 30)->default('draft');
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_reports');
    }
}
