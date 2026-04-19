<?php

namespace App\Models;

use Eloquent;

class Announcement extends Eloquent
{
    protected $fillable = ['title', 'body', 'published_at', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected $dates = ['published_at'];
}
