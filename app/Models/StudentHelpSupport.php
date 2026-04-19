<?php

namespace App\Models;

use Eloquent;

class StudentHelpSupport extends Eloquent
{
    protected $table = 'student_help_support_tickets';

    protected $fillable = ['user_id', 'my_class_id', 'supervisor_id', 'subject', 'message', 'priority', 'status'];
}
