<?php

namespace App\Models;

use Eloquent;

class Assignment extends Eloquent
{
    protected $fillable = ['title', 'course_id', 'teacher_id', 'description', 'due_date'];

    protected $dates = ['due_date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(StudentAssignmentSubmission::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\User::class, 'teacher_id');
    }
}
