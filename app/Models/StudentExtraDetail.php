<?php

namespace App\Models;

use Eloquent;

class StudentExtraDetail extends Eloquent
{
    protected $fillable = ['user_id', 'label', 'detail_value', 'notes'];
}
