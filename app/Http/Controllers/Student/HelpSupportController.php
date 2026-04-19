<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentHelpSupport;

class HelpSupportController extends BaseStudentCrudController
{
    protected $modelClass = StudentHelpSupport::class;
    protected $viewTitle = 'Help & Support';
    protected $routeBase = 'student.help-support';
    protected $formFields = ['subject', 'message', 'priority', 'status'];

    protected function rules(): array
    {
        return [
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ];
    }
}
