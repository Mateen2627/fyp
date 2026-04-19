<?php

namespace App\Models;

use Eloquent;

class Attendance extends Eloquent
{
    protected $fillable = ['student_record_id', 'attendance_date', 'status', 'remarks'];

    protected $dates = ['attendance_date'];
}
