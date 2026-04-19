@extends('layouts.master')
@section('page_title', 'Assignment Details')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">{{ $assignment->title }}</h6></div>
    <div class="card-body">
        <p><strong>Course:</strong> {{ optional($assignment->course)->title ?: '-' }}</p>
        <p><strong>Teacher:</strong> {{ optional($assignment->teacher)->name ?: '-' }}</p>
        <p><strong>Due Date:</strong> {{ optional($assignment->due_date)->format('Y-m-d') ?: '-' }}</p>
        <p><strong>Description:</strong><br>{{ $assignment->description ?: '-' }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Your Submission</h6></div>
    <div class="card-body">
        <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Submission Text</label>
                <textarea name="submission_text" class="form-control">{{ old('submission_text', optional($submission)->submission_text) }}</textarea>
            </div>
            <div class="form-group">
                <label>Attachment</label>
                <input type="file" name="attachment" class="form-control">
                @if($submission && $submission->attachment_path)
                    <small class="text-muted d-block mt-1">Uploaded: {{ $submission->attachment_path }}</small>
                @endif
            </div>
            <button class="btn btn-primary">Submit Assignment</button>
            <a href="{{ route('student.assignments.index') }}" class="btn btn-light">Back</a>
        </form>

        @if($submission)
            <hr>
            <p><strong>Submitted At:</strong> {{ optional($submission->submitted_at)->format('Y-m-d H:i') ?: '-' }}</p>
            <p><strong>Marks:</strong> {{ is_null($submission->marks) ? 'Pending evaluation' : $submission->marks }}</p>
            <p><strong>Teacher Feedback:</strong> {{ $submission->teacher_feedback ?: '-' }}</p>
        @endif
    </div>
</div>
@endsection
