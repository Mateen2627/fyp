<?php

namespace App\Models;

use Eloquent;

class StudentQuiz extends Eloquent
{
    protected $fillable = ['user_id', 'title', 'course_name', 'description', 'total_marks', 'scheduled_at'];

    protected $dates = ['scheduled_at'];
}
