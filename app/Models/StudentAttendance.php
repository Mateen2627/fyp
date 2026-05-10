<?php

namespace App\Models;

use App\User;
use Eloquent;

class StudentAttendance extends Eloquent
{
    protected $table = 'student_attendance_records';

    protected $fillable = ['user_id', 'attendance_date', 'status', 'mode', 'facial_verified', 'remarks', 'marked_by'];

    protected $dates = ['attendance_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function studentRecord()
    {
        return $this->hasOne(StudentRecord::class, 'user_id', 'user_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
