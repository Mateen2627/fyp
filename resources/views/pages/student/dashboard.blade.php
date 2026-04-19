@extends('layouts.master')
@section('page_title', 'Student Dashboard')
@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="mb-1">Welcome, {{ Auth::user()->name }}</h5>
        <p class="text-muted mb-0">Track your assignments, quizzes, attendance and support requests in one place.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Assignments</p>
                <h3 class="mb-0">{{ $submittedAssignments }}/{{ $totalAssignments }}</h3>
                <small class="text-muted">Submitted / Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Pending Assignments</p>
                <h3 class="mb-0">{{ $pendingAssignments }}</h3>
                <small class="text-muted">Need submission</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Quizzes</p>
                <h3 class="mb-0">{{ $attemptedQuizzes }}/{{ $totalQuizzes }}</h3>
                <small class="text-muted">Attempted / Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Pending Quizzes</p>
                <h3 class="mb-0">{{ $pendingQuizzes }}</h3>
                <small class="text-muted">Need attempt</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Present Days</p>
                <h3 class="mb-0">{{ $attendancePresent }}</h3>
                <small class="text-muted">Attendance status: present</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Support Tickets</p>
                <h3 class="mb-0">{{ $supportTickets }}</h3>
                <small class="text-muted">Total raised</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.courses.index') }}">Courses</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.assignments.index') }}">Assignments</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.quizzes.index') }}">Quizzes</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.attendance.index') }}">Attendance</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.reports.index') }}">Reports</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.extra-details.index') }}">Extra Details</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('student.help-support.index') }}">Help & Support</a></div>
</div>
@endsection
