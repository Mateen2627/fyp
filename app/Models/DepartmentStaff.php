<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentStaff extends Model
{
    protected $table = 'department_staff';
    protected $fillable = ['department_id', 'user_id', 'is_hod', 'is_coordinator'];
    protected $casts = ['is_hod' => 'boolean', 'is_coordinator' => 'boolean'];

    public function department()
    {
        return $this->belongsTo(ClassType::class, 'department_id');
    }
}
