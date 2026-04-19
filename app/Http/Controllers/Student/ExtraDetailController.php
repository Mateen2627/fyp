<?php

namespace App\Http\Controllers\Student;

use App\Models\StudentExtraDetail;

class ExtraDetailController extends BaseStudentCrudController
{
    protected $modelClass = StudentExtraDetail::class;
    protected $viewTitle = 'Extra Details';
    protected $routeBase = 'student.extra-details';
    protected $formFields = ['label', 'detail_value', 'notes'];

    protected function rules(): array
    {
        return [
            'label' => 'required|string|max:120',
            'detail_value' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
}
