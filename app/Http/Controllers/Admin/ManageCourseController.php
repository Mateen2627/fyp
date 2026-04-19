<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MyClass;
use Illuminate\Http\Request;

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
            'title' => 'required|string|max:200',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'my_class_id' => 'nullable|exists:my_classes,id',
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
            'title' => 'required|string|max:200',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'my_class_id' => 'nullable|exists:my_classes,id',
        ]);

        $course->update($data);
        return redirect()->route('admin.courses.index')->with('flash_success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('flash_success', 'Course deleted successfully.');
    }
}
