@extends('layouts.master')
@section('page_title', 'Teacher Dashboard')
@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Assigned Courses</p>
                <h3 class="mb-0">{{ $stats['courses_assigned'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Managed Classes</p>
                <h3 class="mb-0">{{ $stats['classes_managed'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Assignments Created</p>
                <h3 class="mb-0">{{ $stats['assignments_created'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Quizzes Created</p>
                <h3 class="mb-0">{{ $stats['quizzes_created'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Assignment Submissions</p>
                <h3 class="mb-0">{{ $stats['assignment_submissions'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Quiz Attempts</p>
                <h3 class="mb-0">{{ $stats['quiz_attempts'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Attendance Records</p>
                <h3 class="mb-0">{{ $stats['attendance_records'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('teacher.courses.index') }}">My Courses</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('teacher.classes.index') }}">My Classes & Timetable</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('teacher.assignments.index') }}">Assignments (Create/Review/Mark)</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('teacher.quizzes.index') }}">Quizzes (Create/Review/Mark)</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('teacher.attendance.index') }}">Student Attendance</a></div>
</div>
@endsection
