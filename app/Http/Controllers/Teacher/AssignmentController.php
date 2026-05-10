<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\StudentAssignmentSubmission;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $assignments = Assignment::with(['course', 'my_class'])->where('teacher_id', Auth::id())->latest()->paginate(20);

        return view('pages.teacher.modules.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $pairs = TeacherAssignment::with(['course', 'my_class'])->where('teacher_id', Auth::id())->get();
        return view('pages.teacher.modules.assignments.create', compact('pairs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'course_id' => 'required|integer',
            'my_class_id' => 'required|integer',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'due_date' => 'nullable|date',
        ]);

        $isAllowedPair = TeacherAssignment::where('teacher_id', Auth::id())
            ->where('course_id', $data['course_id'])
            ->where('my_class_id', $data['my_class_id'])
            ->exists();
        abort_unless($isAllowedPair, 403);

        $data['teacher_id'] = Auth::id();
        $data['attachment_path'] = $request->hasFile('attachment') ? $request->file('attachment')->store('assignment_materials', 'public') : null;

        Assignment::create($data);

        return redirect()->route('teacher.assignments.index')->with('flash_success', 'Assignment created.');
    }

    public function edit(Assignment $assignment)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);

        return view('pages.teacher.modules.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'due_date' => 'nullable|date',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('assignment_materials', 'public');
        }

        $assignment->update($data);

        return redirect()->route('teacher.assignments.index')->with('flash_success', 'Assignment updated.');
    }


    public function viewMaterial(Assignment $assignment)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        abort_unless($assignment->attachment_path && Storage::disk('public')->exists($assignment->attachment_path), 404);

        return $this->inlinePdf($assignment->attachment_path);
    }

    public function downloadMaterial(Assignment $assignment)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        abort_unless($assignment->attachment_path && Storage::disk('public')->exists($assignment->attachment_path), 404);

        return Storage::disk('public')->download($assignment->attachment_path);
    }

    public function show(Assignment $assignment)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        $submissions = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->latest()->get();

        return view('pages.teacher.modules.assignments.show', compact('assignment', 'submissions'));
    }


    public function viewSubmission(Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        abort_unless($submission->attachment_path && Storage::disk('public')->exists($submission->attachment_path), 404);

        return $this->inlinePdf($submission->attachment_path);
    }

    public function downloadSubmission(Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        abort_unless($submission->attachment_path && Storage::disk('public')->exists($submission->attachment_path), 404);

        return Storage::disk('public')->download($submission->attachment_path);
    }


    private function inlinePdf(string $path)
    {
        return response()->file(Storage::disk('public')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    public function mark(Request $request, Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);

        if (!is_null($submission->marks)) {
            return back()->with('flash_danger', 'Marks already assigned and cannot be overridden.');
        }

        $data = $request->validate([
            'marks' => 'required|numeric|min:0|max:100',
            'teacher_feedback' => 'nullable|string',
        ]);
        $submission->update($data);

        return back()->with('flash_success', 'Submission evaluated.');
    }
}
