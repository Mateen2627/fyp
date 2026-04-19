<?php

namespace App\Models;

use Eloquent;

class StudentFaceProfile extends Eloquent
{
    protected $fillable = ['user_id', 'face_token', 'face_image_path', 'is_active'];
}
