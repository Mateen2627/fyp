<?php

namespace App\Models;

use App\User;
use Eloquent;

class StudentAttendance extends Eloquent
{
    protected $table = 'student_attendance_records';

    protected $fillable = ['user_id', 'attendance_date', 'status', 'mode', 'facial_verified', 'remarks', 'marked_by', 'time_table_id', 'course_id', 'my_class_id', 'section_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    protected $dates = ['attendance_date'];

    public function setAttendanceDateAttribute($value)
    {
        $this->attributes['attendance_date'] = \Carbon\Carbon::parse($value)->toDateString();
    }

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
