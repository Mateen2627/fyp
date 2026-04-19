@extends('layouts.master')
@section('page_title', 'Review Quiz Attempts')
@section('content')
<div class="card mb-3"><div class="card-body"><h6>{{ $quiz->title }}</h6><p class="mb-2">{{ $quiz->description ?: "-" }}</p>@if($quiz->attachment_path)<a href="{{ route('teacher.quizzes.download', $quiz->id) }}" class="btn btn-sm btn-outline-primary">Download Quiz File</a>@endif</div></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Student ID</th><th>Submitted</th><th>Answers</th><th>Attachment</th><th>Marks/Feedback</th></tr></thead><tbody>
@forelse($attempts as $attempt)
<tr>
<td>{{ $attempt->student_id }}</td>
<td>{{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?: '-' }}</td>
<td>{{ $attempt->answers ?: '-' }}</td>
<td>
    @if($attempt->attachment_path)
        <a href="{{ route('teacher.quizzes.attempts.download', [$quiz->id, $attempt->id]) }}" class="btn btn-sm btn-outline-primary">Download PDF</a>
    @else - @endif
</td>
<td>
<form action="{{ route('teacher.quizzes.mark', [$quiz->id, $attempt->id]) }}" method="post">
@csrf
<input type="number" step="0.01" min="0" max="100" name="marks" value="{{ $attempt->marks }}" class="form-control mb-1" placeholder="Marks" {{ !is_null($attempt->marks) ? 'disabled' : '' }}>
<textarea name="teacher_feedback" class="form-control mb-1" placeholder="Feedback" {{ !is_null($attempt->marks) ? 'disabled' : '' }}>{{ $attempt->teacher_feedback }}</textarea>
@if(is_null($attempt->marks))<button class="btn btn-sm btn-primary">Save</button>@else <span class="badge badge-success">Locked</span>@endif
</form>
</td>
</tr>
@empty<tr><td colspan="5" class="text-center">No attempts yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
