<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT');
    }

    public function index(){ $records = Course::latest()->paginate(20); return view('pages.support_team.modules.index', ['records' => $records, 'module' => 'courses', 'title' => 'Courses']); }
    public function create(){ return view('pages.support_team.modules.create', ['module' => 'courses', 'title' => 'Course']); }
    public function store(Request $request){ $data = $request->validate(['title' => 'required|string|max:200','code' => 'nullable|string|max:50','description' => 'nullable|string','my_class_id' => 'nullable|integer']); Course::create($data); return redirect()->route('courses.index')->with('flash_success', 'Course created successfully.'); }
    public function show(Course $course){ return view('pages.support_team.modules.show', ['record' => $course, 'module' => 'courses', 'title' => 'Course']); }
    public function edit(Course $course){ return view('pages.support_team.modules.edit', ['record' => $course, 'module' => 'courses', 'title' => 'Course']); }
    public function update(Request $request, Course $course){ $data = $request->validate(['title' => 'required|string|max:200','code' => 'nullable|string|max:50','description' => 'nullable|string','my_class_id' => 'nullable|integer']); $course->update($data); return redirect()->route('courses.index')->with('flash_success', 'Course updated successfully.'); }
    public function destroy(Course $course){ $course->delete(); return redirect()->route('courses.index')->with('flash_success', 'Course deleted successfully.'); }
}
