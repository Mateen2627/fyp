@extends('layouts.master')
@section('page_title', 'Child Academic Records')
@section('content')

<div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h5 class="mb-1">{{ $student->user->name }} - Academic Overview</h5>
            <div class="text-muted">Class: {{ optional($student->my_class)->name }} {{ optional($student->section)->name }} | Year: {{ $academicYear }}</div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('my_children') }}" class="btn btn-light"><i class="icon-arrow-left52"></i> Back to My Children</a>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><h3>{{ number_format($summary['mid_total'], 2) }}</h3><span>Total Mid Marks</span></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h3>{{ number_format($summary['final_total'], 2) }}</h3><span>Total Final Marks</span></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h3>{{ $summary['assignments_submitted'] }}/{{ $summary['assignments_total'] }}</h3><span>Assignments Submitted</span></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h3>{{ $summary['quizzes_submitted'] }}/{{ $summary['quizzes_total'] }}</h3><span>Quizzes Submitted</span></div></div></div>
</div>

<div class="card mb-3">
    <div class="card-header"><h6 class="card-title mb-0">Subject Marks (Mid / Final)</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Subject</th><th>Teacher</th><th>Mid</th><th>Final</th><th>Total</th></tr></thead>
            <tbody>
            @forelse($marks as $mark)
                <tr>
                    <td>{{ optional($mark->subject)->name ?: '—' }}</td>
                    <td>{{ optional($mark->teacher)->name ?: '—' }}</td>
                    <td>{{ $mark->mid_marks !== null ? number_format($mark->mid_marks, 2) : '-' }}</td>
                    <td>{{ $mark->final_marks !== null ? number_format($mark->final_marks, 2) : '-' }}</td>
                    <td>{{ $mark->total_marks !== null ? number_format($mark->total_marks, 2) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No marks found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><h6 class="card-title mb-0">Assignments</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Title</th><th>Course</th><th>Teacher</th><th>Due Date</th><th>Status</th><th>Marks</th></tr></thead>
            <tbody>
            @forelse($assignments as $assignment)
                @php($submission = $assignmentSubmissionMap->get($assignment->id))
                <tr>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ optional($assignment->course)->title ?: '—' }}</td>
                    <td>{{ optional($assignment->teacher)->name ?: '—' }}</td>
                    <td>{{ optional($assignment->due_date)->format('Y-m-d') ?: '—' }}</td>
                    <td>{{ $submission ? 'Submitted' : 'Pending' }}</td>
                    <td>{{ $submission && $submission->marks !== null ? number_format($submission->marks, 2) : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No assignments found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="card-title mb-0">Quizzes</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Title</th><th>Course</th><th>Teacher</th><th>Scheduled</th><th>Status</th><th>Marks</th></tr></thead>
            <tbody>
            @forelse($quizzes as $quiz)
                @php($attempt = $quizAttemptMap->get($quiz->id))
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>{{ optional($quiz->course)->title ?: '—' }}</td>
                    <td>{{ optional($quiz->teacher)->name ?: '—' }}</td>
                    <td>{{ optional($quiz->scheduled_at)->format('Y-m-d H:i') ?: '—' }}</td>
                    <td>{{ $attempt ? 'Submitted' : 'Pending' }}</td>
                    <td>{{ $attempt && $attempt->marks !== null ? number_format($attempt->marks, 2) : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No quizzes found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
