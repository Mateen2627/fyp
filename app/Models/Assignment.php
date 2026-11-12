<?php

namespace App\Models;

use Eloquent;

class Assignment extends Eloquent
{
    protected $fillable = ['title', 'course_id', 'teacher_id', 'my_class_id', 'section_id', 'description', 'attachment_path', 'due_date', 'total_marks'];

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
        return $this->belongsTo(\App\User::class, 'teacher_id')->withTrashed();
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }
}
