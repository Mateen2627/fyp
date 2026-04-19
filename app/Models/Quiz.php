<?php

namespace App\Models;

use Eloquent;

class Quiz extends Eloquent
{
    protected $fillable = ['title', 'course_id', 'description', 'total_marks', 'scheduled_at'];

    protected $dates = ['scheduled_at'];
}
