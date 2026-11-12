<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassType;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManageClassController extends Controller
{
    public function __construct() { $this->middleware('teamSA'); }
    public function index() { return view('pages.admin.modules.classes.index', ['records' => MyClass::withCount('student_record')->latest()->paginate(20)]); }
    public function create() { return view('pages.admin.modules.classes.create', $this->formData()); }
    public function edit(MyClass $class) { return view('pages.admin.modules.classes.edit', $this->formData($class)); }
    public function show(MyClass $class) { $class->load(['class_type', 'student_record.user']); return view('pages.admin.modules.classes.show', ['record' => $class]); }
    public function store(Request $request) { $this->save($request); return redirect()->route('admin.classes.index')->with('flash_success', 'Semester and sections created.'); }
    public function update(Request $request, MyClass $class) { $this->save($request, $class); return redirect()->route('admin.classes.index')->with('flash_success', 'Semester updated. Move students using Manage Users, selecting both semester and section.'); }

    public function destroy(MyClass $class)
    {
        if (StudentRecord::where('my_class_id', $class->id)->exists() || DB::table('courses')->where('my_class_id', $class->id)->exists() || DB::table('time_table_records')->where('my_class_id', $class->id)->exists()) {
            throw ValidationException::withMessages(['class' => 'This semester has students, subjects, or timetables. Reassign or remove those records first.']);
        }
        $class->delete();
        return back()->with('flash_success', 'Semester deleted.');
    }

    private function save(Request $request, $class = null)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('my_classes')->where('class_type_id', $request->class_type_id)->ignore($class ? $class->id : null)],
            'class_type_id' => 'required|integer|exists:class_types,id',
            'sections' => 'required|string|max:100',
        ]);
        if ($class && (int) $class->class_type_id !== (int) $data['class_type_id']) {
            throw ValidationException::withMessages(['class_type_id' => 'A semester cannot be moved to another department. Create a new semester instead.']);
        }
        $sections = array_unique(array_filter(array_map('trim', explode(',', $data['sections']))));
        if (!$sections) { throw ValidationException::withMessages(['sections' => 'Enter at least one section.']); }
        DB::transaction(function () use ($data, $class, $sections) {
            $values = collect($data)->only(['name', 'class_type_id'])->all();
            if ($class) { $class->update($values); } else { $class = MyClass::create($values); }
            foreach ($sections as $name) { Section::firstOrCreate(['my_class_id' => $class->id, 'name' => $name], ['active' => 1]); }
        });
    }
    private function formData($record = null) { return ['record' => $record, 'classTypes' => ClassType::orderBy('name')->get()]; }
}
