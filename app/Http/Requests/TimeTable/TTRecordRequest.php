<?php

namespace App\Http\Requests\TimeTable;

use Illuminate\Foundation\Http\FormRequest;

class TTRecordRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if($this->method() === 'POST'){
            return [
                'name' => 'required|string|min:3|max:100|unique:time_table_records',
                'my_class_id' => 'required|integer|exists:my_classes,id',
                'section_id' => 'required|integer|exists:sections,id',
            ];
        }

        return [
            'name' => ['required', 'string', 'min:3', 'max:100', \Illuminate\Validation\Rule::unique('time_table_records', 'name')->ignore((int) $this->route('ttr'))],
            'my_class_id' => 'required|integer|exists:my_classes,id',
            'section_id' => 'required|integer|exists:sections,id',
        ];
    }

    public function attributes()
    {
        return  [
            'my_class_id' => 'Class',
        ];
    }

}
