@extends('layouts.master')
@section('page_title', 'Review Assignment Submissions')
@section('content')
<div class="card mb-3"><div class="card-body"><h6>{{ $assignment->title }}</h6></div></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Student ID</th><th>Submitted</th><th>Text</th><th>Attachment</th><th>Marks/Feedback</th></tr></thead><tbody>
@forelse($submissions as $submission)
<tr>
<td>{{ $submission->student_id }}</td>
<td>{{ optional($submission->submitted_at)->format('Y-m-d H:i') ?: '-' }}</td>
<td>{{ $submission->submission_text ?: '-' }}</td>
<td>{{ $submission->attachment_path ?: '-' }}</td>
<td>
<form action="{{ route('teacher.assignments.mark', [$assignment->id, $submission->id]) }}" method="post">
@csrf
<input type="number" step="0.01" min="0" max="100" name="marks" value="{{ $submission->marks }}" class="form-control mb-1" placeholder="Marks">
<textarea name="teacher_feedback" class="form-control mb-1" placeholder="Feedback">{{ $submission->teacher_feedback }}</textarea>
<button class="btn btn-sm btn-primary">Save</button>
</form>
</td>
</tr>
@empty<tr><td colspan="5" class="text-center">No submissions yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
