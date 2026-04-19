<?php

namespace App\Models;

use Eloquent;

class StudentAttendance extends Eloquent
{
    protected $table = 'student_attendance_records';

    protected $fillable = ['user_id', 'attendance_date', 'status', 'mode', 'facial_verified', 'remarks'];

    protected $dates = ['attendance_date'];
}
