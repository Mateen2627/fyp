@extends('layouts.master')
@section('page_title', 'Quizzes')
@section('content')
<div class="mb-2"><a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">Add Quiz</a></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Title</th><th>Class</th><th>Course</th><th>Scheduled</th><th>Action</th></tr></thead><tbody>
@forelse($quizzes as $quiz)
<tr><td>{{ $quiz->title }}</td><td>{{ optional($quiz->my_class)->name ?: '-' }}</td><td>{{ optional($quiz->course)->title ?: '-' }}</td><td>{{ optional($quiz->scheduled_at)->format('Y-m-d H:i') ?: '-' }}</td><td><a href="{{ route('teacher.quizzes.show', $quiz->id) }}" class="btn btn-sm btn-info">Review Attempts</a></td></tr>
@empty<tr><td colspan="5" class="text-center">No quizzes created yet.</td></tr>@endforelse
</tbody></table></div><div class="card-footer">{{ $quizzes->links() }}</div></div>
@endsection
