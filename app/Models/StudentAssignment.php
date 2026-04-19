<?php

namespace App\Models;

use Eloquent;

class StudentAssignment extends Eloquent
{
    protected $fillable = ['user_id', 'title', 'course_name', 'description', 'due_date', 'status'];

    protected $dates = ['due_date'];
}
