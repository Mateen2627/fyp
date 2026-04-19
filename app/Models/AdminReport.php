<?php

namespace App\Models;

use Eloquent;

class AdminReport extends Eloquent
{
    protected $fillable = ['title', 'category', 'content', 'status', 'created_by'];
}
