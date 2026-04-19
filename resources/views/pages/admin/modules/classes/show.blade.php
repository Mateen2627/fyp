@extends('layouts.master')
@section('page_title', 'Class Details')
@section('content')
<div class="card mb-3"><div class="card-body"><h6 class="mb-1">{{ $record->name }}</h6><div class="text-muted">Type: {{ optional($record->class_type)->name ?: '-' }}</div></div></div>
<div class="card">
    <div class="card-header"><h6 class="mb-0">Students in this class</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Record ID</th><th>Student Name</th><th>User ID</th></tr></thead>
            <tbody>
            @forelse($record->student_record as $sr)
                <tr><td>{{ $sr->id }}</td><td>{{ optional($sr->user)->name ?: '-' }}</td><td>{{ $sr->user_id }}</td></tr>
            @empty
                <tr><td colspan="3" class="text-center">No students assigned.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
