<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentQuiz;

class QuizController extends BaseStudentCrudController
{
    protected $modelClass = StudentQuiz::class;
    protected $viewTitle = 'Quizzes';
    protected $routeBase = 'student.quizzes';
    protected $formFields = ['title', 'course_name', 'description', 'total_marks', 'scheduled_at'];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'course_name' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'total_marks' => 'nullable|integer|min:0',
            'scheduled_at' => 'nullable|date',
        ];
    }
}
