<?php

namespace App\Models;

use Eloquent;

class Assignment extends Eloquent
{
    protected $fillable = ['title', 'course_id', 'description', 'due_date'];

    protected $dates = ['due_date'];
}
