<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentHelpSupport;
use App\Models\StudentRecord;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpSupportController extends BaseStudentCrudController
{
    protected $modelClass = StudentHelpSupport::class;
    protected $viewTitle = 'Help & Support';
    protected $routeBase = 'student.help-support';
    protected $formFields = ['subject', 'message', 'priority'];

    protected function rules(): array
    {
        return [
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        $supervisorId = TeacherAssignment::where('my_class_id', $record->my_class_id)->value('teacher_id');

        $data['user_id'] = Auth::id();
        $data['my_class_id'] = $record->my_class_id;
        $data['supervisor_id'] = $supervisorId;
        $data['status'] = 'open';

        StudentHelpSupport::create($data);

        return redirect()->route('student.help-support.index')->with('flash_success', 'Support ticket submitted to your class supervisor.');
    }
}
