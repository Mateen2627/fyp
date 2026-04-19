<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassType;
use App\Models\MyClass;
use App\Models\StudentRecord;
use Illuminate\Http\Request;

class ManageClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.classes.index', [
            'records' => MyClass::withCount('student_record')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('pages.admin.modules.classes.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'class_type_id' => 'nullable|exists:class_types,id',
            'student_record_ids' => 'nullable|array',
            'student_record_ids.*' => 'integer|exists:student_records,id',
        ]);

        $class = MyClass::create([
            'name' => $data['name'],
            'class_type_id' => $data['class_type_id'] ?? null,
        ]);

        if (!empty($data['student_record_ids'])) {
            StudentRecord::whereIn('id', $data['student_record_ids'])->update(['my_class_id' => $class->id]);
        }

        return redirect()->route('admin.classes.index')->with('flash_success', 'Class created and students assigned successfully.');
    }

    public function show(MyClass $class)
    {
        $class->load(['class_type', 'student_record.user']);
        return view('pages.admin.modules.classes.show', ['record' => $class]);
    }

    public function edit(MyClass $class)
    {
        $class->load('student_record');
        return view('pages.admin.modules.classes.edit', array_merge($this->formData(), ['record' => $class]));
    }

    public function update(Request $request, MyClass $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'class_type_id' => 'nullable|exists:class_types,id',
            'student_record_ids' => 'nullable|array',
            'student_record_ids.*' => 'integer|exists:student_records,id',
        ]);

        $class->update([
            'name' => $data['name'],
            'class_type_id' => $data['class_type_id'] ?? null,
        ]);

        $selected = collect($data['student_record_ids'] ?? [])->map(fn($id) => (int) $id);

        if ($selected->isNotEmpty()) {
            StudentRecord::whereIn('id', $selected->all())->update(['my_class_id' => $class->id]);
        }

        return redirect()->route('admin.classes.index')->with('flash_success', 'Class updated successfully.');
    }

    public function destroy(MyClass $class)
    {
        if (StudentRecord::where('my_class_id', $class->id)->exists()) {
            return redirect()->route('admin.classes.index')->with('flash_danger', 'Cannot delete class with assigned students. Reassign them first.');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')->with('flash_success', 'Class deleted successfully.');
    }

    private function formData(): array
    {
        return [
            'classTypes' => ClassType::orderBy('name')->get(),
            'studentRecords' => StudentRecord::with('user')->orderBy('id')->get(),
        ];
    }
}
