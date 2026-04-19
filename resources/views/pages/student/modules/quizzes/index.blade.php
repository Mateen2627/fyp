@extends('layouts.master')
@section('page_title', 'Quizzes')
@section('content')
<div class="card">
    <div class="card-header"><h6 class="mb-0">Quizzes For Your Class</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Title</th><th>Course</th><th>Teacher</th><th>Scheduled</th><th>Total Marks</th><th>Your Marks</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($quizzes as $quiz)
                @php($attempt = $attemptMap->get($quiz->id))
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>{{ optional($quiz->course)->title ?: '-' }}</td>
                    <td>{{ optional($quiz->teacher)->name ?: '-' }}</td>
                    <td>{{ optional($quiz->scheduled_at)->format('Y-m-d H:i') ?: '-' }}</td>
                    <td>{{ $quiz->total_marks ?: '-' }}</td>
                    <td>{{ $attempt && !is_null($attempt->marks) ? $attempt->marks : 'Pending' }}</td>
                    <td><a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn btn-sm btn-primary">View / Submit</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No quizzes assigned to your class.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $quizzes->links() }}</div>
</div>
@endsection
