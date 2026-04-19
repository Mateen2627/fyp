<?php

namespace App\Models;

use Eloquent;

class StudentReport extends Eloquent
{
    protected $fillable = ['student_record_id', 'title', 'report', 'reported_at'];

    protected $dates = ['reported_at'];
}
