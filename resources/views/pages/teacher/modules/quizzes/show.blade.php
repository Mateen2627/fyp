@extends('layouts.master')
@section('page_title', 'Review Quiz Attempts')
@section('content')
<div class="card mb-3"><div class="card-body"><h6>{{ $quiz->title }}</h6></div></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Student ID</th><th>Submitted</th><th>Answers</th><th>Marks/Feedback</th></tr></thead><tbody>
@forelse($attempts as $attempt)
<tr>
<td>{{ $attempt->student_id }}</td>
<td>{{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?: '-' }}</td>
<td>{{ $attempt->answers ?: '-' }}</td>
<td>
<form action="{{ route('teacher.quizzes.mark', [$quiz->id, $attempt->id]) }}" method="post">
@csrf
<input type="number" step="0.01" min="0" max="100" name="marks" value="{{ $attempt->marks }}" class="form-control mb-1" placeholder="Marks">
<textarea name="teacher_feedback" class="form-control mb-1" placeholder="Feedback">{{ $attempt->teacher_feedback }}</textarea>
<button class="btn btn-sm btn-primary">Save</button>
</form>
</td>
</tr>
@empty<tr><td colspan="4" class="text-center">No attempts yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
