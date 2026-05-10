<?php

namespace App\Models;

use App\User;
use Eloquent;

class SubjectAssessmentMark extends Eloquent
{
    protected $fillable = [
        'teacher_id', 'student_id', 'subject_id', 'my_class_id', 'section_id', 'academic_year',
        'mid_marks', 'final_marks', 'total_marks', 'remarks',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
