@extends('layouts.master')
@section('page_title', 'Quiz Details')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">{{ $quiz->title }}</h6></div>
    <div class="card-body">
        <p><strong>Course:</strong> {{ optional($quiz->course)->title ?: '-' }}</p>
        <p><strong>Teacher:</strong> {{ optional($quiz->teacher)->name ?: '-' }}</p>
        <p><strong>Scheduled At:</strong> {{ optional($quiz->scheduled_at)->format('Y-m-d H:i') ?: '-' }}</p>
        <p><strong>Total Marks:</strong> {{ $quiz->total_marks ?: '-' }}</p>
        <p><strong>Description:</strong><br>{{ $quiz->description ?: '-' }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Your Attempt</h6></div>
    <div class="card-body">
        @if(!$attempt)
        <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Your Answers</label>
                <textarea name="answers" class="form-control">{{ old('answers', optional($attempt)->answers) }}</textarea>
            </div>
            <div class="form-group">
                <label>Attachment</label>
                <input type="file" name="attachment" class="form-control">
                @if($attempt && $attempt->attachment_path)
                    <small class="text-muted d-block mt-1">Uploaded: {{ $attempt->attachment_path }}</small>
                @endif
            </div>
            <button class="btn btn-primary">Submit Quiz</button>
            <a href="{{ route('student.quizzes.index') }}" class="btn btn-light">Back</a>
        </form>
        @else
            <div class="alert alert-info">You already submitted this quiz. Resubmission is disabled.</div>
        @endif

        @if($attempt)
            <hr>
            <p><strong>Submitted At:</strong> {{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?: '-' }}</p>
            <p><strong>Marks:</strong> {{ is_null($attempt->marks) ? 'Pending evaluation' : $attempt->marks }}</p>
            <p><strong>Teacher Feedback:</strong> {{ $attempt->teacher_feedback ?: '-' }}</p>
        @endif
    </div>
</div>
@endsection
