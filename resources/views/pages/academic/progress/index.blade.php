@extends('layouts.master')
@section('page_title', 'Department Progress')
@section('content')
<div class="card"><div class="card-header"><h5 class="mb-0">Department Progress</h5></div>
<div class="card-body"><p>View class attendance, assignments, quizzes, submissions, and marks. These department reports are read-only.</p>
    @if(Auth::user()->isHod())<a href="{{ route('hod.assign-teachers.index') }}" class="btn btn-primary mr-2">Manage Teaching Assignments</a>@endif
    @if(Auth::user()->isCoordinator())<a href="{{ route('tt.index') }}" class="btn btn-primary mr-2">Manage Timetables</a>@endif
    <a href="{{ route('teacher.dashboard') }}" class="btn btn-light">My Teaching Dashboard</a>
</div><div class="table-responsive"><table class="table"><thead><tr><th>Department</th><th>Semester</th><th>Sections</th><th>Students</th><th>Progress</th></tr></thead>
<tbody>@forelse($classes as $class)<tr><td>{{ optional($class->class_type)->name }}</td><td>{{ $class->name }}</td><td>{{ $class->section->pluck('name')->implode(', ') }}</td><td>{{ $class->student_record_count }}</td><td><a href="{{ route('academic.progress.show', $class) }}">View Class Progress</a></td></tr>@empty<tr><td colspan="5">No classes in your departments.</td></tr>@endforelse</tbody></table></div></div>
@endsection
