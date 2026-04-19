<?php

namespace App\Models;

use Eloquent;

class StudentQuizAttempt extends Eloquent
{
    protected $fillable = ['quiz_id', 'student_id', 'answers', 'marks', 'teacher_feedback', 'submitted_at'];

    protected $dates = ['submitted_at'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
