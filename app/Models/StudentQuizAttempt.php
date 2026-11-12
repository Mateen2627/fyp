<?php

namespace App\Models;

use Eloquent;

class StudentQuizAttempt extends Eloquent
{
    protected $fillable = ['quiz_id', 'student_id', 'answers', 'attachment_path', 'marks', 'teacher_feedback', 'submitted_at'];

    protected $dates = ['submitted_at'];

    public function student()
    {
        return $this->belongsTo(\App\User::class, 'student_id')->withTrashed();
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
