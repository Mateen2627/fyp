<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentAssignment;

class AssignmentController extends BaseStudentCrudController
{
    protected $modelClass = StudentAssignment::class;
    protected $viewTitle = 'Assignments';
    protected $routeBase = 'student.assignments';
    protected $formFields = ['title', 'course_name', 'description', 'due_date', 'status'];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'course_name' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,submitted,graded',
        ];
    }
}
