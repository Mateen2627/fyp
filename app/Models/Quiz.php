<?php

namespace App\Models;

use Eloquent;

class Quiz extends Eloquent
{
    protected $fillable = ['title', 'course_id', 'teacher_id', 'my_class_id', 'description', 'attachment_path', 'total_marks', 'scheduled_at'];

    protected $dates = ['scheduled_at'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attempts()
    {
        return $this->hasMany(StudentQuizAttempt::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\User::class, 'teacher_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }
}
