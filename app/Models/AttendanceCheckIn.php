<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCheckIn extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['attendance_date'];

    public function setAttendanceDateAttribute($value)
    {
        $this->attributes['attendance_date'] = \Carbon\Carbon::parse($value)->toDateString();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function lesson()
    {
        return $this->belongsTo(TimeTable::class, 'time_table_id');
    }
}
