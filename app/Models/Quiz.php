<?php

namespace App\Models;

use Eloquent;

class Quiz extends Eloquent
{
    protected $fillable = ['title', 'course_id', 'teacher_id', 'description', 'total_marks', 'scheduled_at'];

    protected $dates = ['scheduled_at'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attempts()
    {
        return $this->hasMany(StudentQuizAttempt::class);
    }
}
