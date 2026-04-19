<?php

namespace App\Models;

use Eloquent;

class TeacherAssignment extends Eloquent
{
    protected $fillable = ['course_id', 'teacher_id', 'assigned_by', 'notes'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\User::class, 'teacher_id');
    }
}
