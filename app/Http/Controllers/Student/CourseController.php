<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentCourse;

class CourseController extends BaseStudentCrudController
{
    protected $modelClass = StudentCourse::class;
    protected $viewTitle = 'Courses';
    protected $routeBase = 'student.courses';
    protected $formFields = ['title', 'course_code', 'description'];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'course_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ];
    }
}
