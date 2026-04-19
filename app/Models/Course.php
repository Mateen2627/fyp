<?php

namespace App\Models;

use Eloquent;

class Course extends Eloquent
{
    protected $fillable = ['title', 'code', 'description', 'my_class_id'];
}
