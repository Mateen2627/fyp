<?php

namespace App\Models;

use Eloquent;

class StudentCourse extends Eloquent
{
    protected $fillable = ['user_id', 'title', 'course_code', 'description'];
}
