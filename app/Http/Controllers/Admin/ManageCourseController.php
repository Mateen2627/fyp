<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ManageCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.courses.index', [
            'records' => Course::latest()->paginate(20),
            'classes' => MyClass::pluck('name', 'id'),
        ]);
    }

    public function create()
    {
        return view('pages.admin.modules.courses.create', ['classes' => MyClass::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100', Rule::unique('courses')->where('my_class_id', $request->my_class_id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('courses')->where('my_class_id', $request->my_class_id)],
            'description' => 'nullable|string',
            'my_class_id' => 'required|integer|exists:my_classes,id',
        ]);

        Course::create($data);
        return redirect()->route('admin.courses.index')->with('flash_success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('pages.admin.modules.courses.edit', [
            'record' => $course,
            'classes' => MyClass::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100', Rule::unique('courses')->where('my_class_id', $request->my_class_id)->ignore($course->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('courses')->where('my_class_id', $request->my_class_id)->ignore($course->id)],
            'description' => 'nullable|string',
            'my_class_id' => 'required|integer|exists:my_classes,id',
        ]);

        if ((int) $course->my_class_id !== (int) $data['my_class_id']) {
            throw ValidationException::withMessages(['my_class_id' => 'Create a new subject to change its semester. Existing teaching assignments and work must retain their semester.']);
        }
        DB::transaction(function () use ($course, $data) {
            $course->update($data);
            if ($course->subject) { $course->subject->update(['name' => $course->title, 'slug' => $course->code ?: $course->subject->slug]); }
        });
        return redirect()->route('admin.courses.index')->with('flash_success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->assignments()->exists() || DB::table('assignments')->where('course_id', $course->id)->exists() || DB::table('quizzes')->where('course_id', $course->id)->exists() || DB::table('time_tables')->where('subject_id', $course->subject_id)->exists()) {
            throw ValidationException::withMessages(['course' => 'This subject has teaching assignments, timetable lessons, or student work. The HOD must revoke teaching assignments first; academic history must be retained.']);
        }
        $course->delete();
        return redirect()->route('admin.courses.index')->with('flash_success', 'Course deleted successfully.');
    }
}
