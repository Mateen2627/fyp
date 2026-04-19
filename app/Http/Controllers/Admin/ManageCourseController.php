<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class ManageCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.courses.index', ['records' => Course::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('pages.admin.modules.courses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'my_class_id' => 'nullable|integer',
        ]);

        Course::create($data);
        return redirect()->route('admin.courses.index')->with('flash_success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('pages.admin.modules.courses.edit', ['record' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'my_class_id' => 'nullable|integer',
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
