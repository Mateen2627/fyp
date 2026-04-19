<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentModuleReport;

class ReportController extends BaseStudentCrudController
{
    protected $modelClass = StudentModuleReport::class;
    protected $viewTitle = 'Reports';
    protected $routeBase = 'student.reports';
    protected $formFields = ['title', 'report_type', 'content'];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'report_type' => 'nullable|string|max:100',
            'content' => 'required|string',
        ];
    }
}
