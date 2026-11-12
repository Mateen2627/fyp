<?php

namespace App\Models;

use Eloquent;

class Course extends Eloquent
{
    protected $fillable = ['title', 'code', 'description', 'my_class_id', 'subject_id', 'teaching_initialized'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'course_id');
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'course_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }
}
