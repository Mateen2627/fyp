<?php

namespace App\Models;

use Eloquent;

class Course extends Eloquent
{
    protected $fillable = ['title', 'code', 'description', 'my_class_id'];

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'course_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }
}
