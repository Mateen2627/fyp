@extends('layouts.master')
@section('page_title', 'Assignments')
@section('content')
<div class="card">
    <div class="card-header"><h6 class="mb-0">Assignments For Your Class</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Title</th><th>Course</th><th>Due Date</th><th>Your Marks</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($assignments as $assignment)
                @php($submission = $submissionMap->get($assignment->id))
                <tr>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ optional($assignment->course)->title ?: '-' }}</td>
                    <td>{{ optional($assignment->due_date)->format('Y-m-d') ?: '-' }}</td>
                    <td>{{ $submission && !is_null($submission->marks) ? $submission->marks : 'Pending' }}</td>
                    <td>{{ $submission ? 'Submitted' : 'Not Submitted' }}</td>
                    <td><a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-sm btn-primary">View / Submit</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No assignments assigned to your class.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $assignments->links() }}</div>
</div>
@endsection
