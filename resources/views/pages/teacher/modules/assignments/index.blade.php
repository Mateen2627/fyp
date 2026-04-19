@extends('layouts.master')
@section('page_title', 'Assignments')
@section('content')
<div class="mb-2"><a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary">Add Assignment</a></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Title</th><th>Class</th><th>Course</th><th>Due Date</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ optional($assignment->my_class)->name ?: '-' }}</td>
                    <td>{{ optional($assignment->course)->title ?: '-' }}</td>
                    <td>{{ optional($assignment->due_date)->format('Y-m-d') ?: '-' }}</td>
                    <td><a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="btn btn-sm btn-info">Review Submissions</a> <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="btn btn-sm btn-secondary">Edit</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No assignments created yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $assignments->links() }}</div>
</div>
@endsection
