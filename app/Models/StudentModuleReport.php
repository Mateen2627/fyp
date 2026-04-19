<?php

namespace App\Models;

use Eloquent;

class StudentModuleReport extends Eloquent
{
    protected $table = 'student_module_reports';

    protected $fillable = ['user_id', 'title', 'report_type', 'content'];
}
