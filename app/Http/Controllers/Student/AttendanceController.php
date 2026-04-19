<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentAttendance;

class AttendanceController extends BaseStudentCrudController
{
    protected $modelClass = StudentAttendance::class;
    protected $viewTitle = 'Attendance';
    protected $routeBase = 'student.attendance';
    protected $formFields = ['attendance_date', 'status', 'remarks'];

    protected function rules(): array
    {
        return [
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ];
    }
}
