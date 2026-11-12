<?php

namespace App\Models;

use Eloquent;

class StudentAssignmentSubmission extends Eloquent
{
    protected $fillable = [
        'assignment_id', 'student_id', 'submission_text', 'attachment_path', 'marks', 'teacher_feedback', 'submitted_at',
    ];

    protected $dates = ['submitted_at'];

    public function student()
    {
        return $this->belongsTo(\App\User::class, 'student_id')->withTrashed();
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
