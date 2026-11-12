<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\LmsAccess;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        $assignments = LmsAccess::scopeTeacherActivities(Assignment::with(['course', 'my_class']))->latest()->paginate(20);

        return view('pages.teacher.modules.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $pairs = LmsAccess::teachingQuery()->with(['course', 'my_class', 'section'])->get();
        return view('pages.teacher.modules.assignments.create', compact('pairs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'course_id' => 'required|integer',
            'my_class_id' => 'required|integer|exists:my_classes,id',
            'section_id' => ['nullable', 'integer', Rule::exists('sections', 'id')->where('my_class_id', $request->my_class_id)],
            'total_marks' => 'nullable|integer|min:1|max:999',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'due_date' => 'nullable|date',
        ]);

        $isAllowedPair = TeacherAssignment::where('teacher_id', Auth::id())
            ->where('course_id', $data['course_id'])
            ->where('my_class_id', $data['my_class_id'])
            ->exists();
        abort_unless($isAllowedPair, 403);

        LmsAccess::assertTeaching($data['course_id'], $data['my_class_id'], $data['section_id'] ?? null);
        $data['teacher_id'] = Auth::id();
        $data['attachment_path'] = $request->hasFile('attachment') ? $request->file('attachment')->store('assignment_materials', 'local') : null;

        Assignment::create($data);

        return redirect()->route('teacher.assignments.index')->with('flash_success', 'Assignment created.');
    }

    public function edit(Assignment $assignment)
    {
        LmsAccess::assertActivityOwner($assignment);

        return view('pages.teacher.modules.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        LmsAccess::assertActivityOwner($assignment);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'total_marks' => 'nullable|integer|min:1|max:999',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'due_date' => 'nullable|date',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('assignment_materials', 'local');
        }

        if (isset($data['total_marks']) && $assignment->submissions()->where('marks', '>', $data['total_marks'])->exists()) {
            throw ValidationException::withMessages(['total_marks' => 'Total marks cannot be lower than an existing awarded mark.']);
        }
        $assignment->update($data);

        return redirect()->route('teacher.assignments.index')->with('flash_success', 'Assignment updated.');
    }


    public function viewMaterial(Assignment $assignment)
    {
        LmsAccess::assertActivityOwner($assignment);
        abort_unless($assignment->attachment_path && Storage::disk('local')->exists($assignment->attachment_path), 404);

        return $this->inlinePdf($assignment->attachment_path);
    }

    public function downloadMaterial(Assignment $assignment)
    {
        LmsAccess::assertActivityOwner($assignment);
        abort_unless($assignment->attachment_path && Storage::disk('local')->exists($assignment->attachment_path), 404);

        return Storage::disk('local')->download($assignment->attachment_path);
    }

    public function show(Assignment $assignment)
    {
        LmsAccess::assertActivityOwner($assignment);
        $submissions = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->latest()->get();

        return view('pages.teacher.modules.assignments.show', compact('assignment', 'submissions'));
    }


    public function viewSubmission(Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        LmsAccess::assertActivityOwner($assignment);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        abort_unless($submission->attachment_path && Storage::disk('local')->exists($submission->attachment_path), 404);

        return $this->inlinePdf($submission->attachment_path);
    }

    public function downloadSubmission(Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        LmsAccess::assertActivityOwner($assignment);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        abort_unless($submission->attachment_path && Storage::disk('local')->exists($submission->attachment_path), 404);

        return Storage::disk('local')->download($submission->attachment_path);
    }


    private function inlinePdf(string $path)
    {
        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    public function mark(Request $request, Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        LmsAccess::assertActivityOwner($assignment);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);

        $data = $request->validate([
            'marks' => 'required|numeric|min:0|max:'.($assignment->total_marks ?: 100),
            'teacher_feedback' => 'nullable|string|max:3000',
        ]);
        $submission->update($data);

        return back()->with('flash_success', 'Submission evaluated.');
    }
}
