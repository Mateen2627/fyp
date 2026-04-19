@extends('layouts.master')
@section('page_title', 'Quiz Details')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">{{ $quiz->title }}</h6></div>
    <div class="card-body">
        <p><strong>Course:</strong> {{ optional($quiz->course)->title ?: '-' }}</p>
        <p><strong>Scheduled At:</strong> {{ optional($quiz->scheduled_at)->format('Y-m-d H:i') ?: '-' }}</p>
        <p><strong>Total Marks:</strong> {{ $quiz->total_marks ?: '-' }}</p>
        <p><strong>Description:</strong><br>{{ $quiz->description ?: '-' }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Your Attempt</h6></div>
    <div class="card-body">
        <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label>Your Answers</label>
                <textarea name="answers" class="form-control" required>{{ old('answers', optional($attempt)->answers) }}</textarea>
            </div>
            <button class="btn btn-primary">Submit Quiz</button>
            <a href="{{ route('student.quizzes.index') }}" class="btn btn-light">Back</a>
        </form>

        @if($attempt)
            <hr>
            <p><strong>Submitted At:</strong> {{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?: '-' }}</p>
            <p><strong>Marks:</strong> {{ is_null($attempt->marks) ? 'Pending evaluation' : $attempt->marks }}</p>
            <p><strong>Teacher Feedback:</strong> {{ $attempt->teacher_feedback ?: '-' }}</p>
        @endif
    </div>
</div>
@endsection
