@extends('layouts.master')
@section('page_title', 'Student Dashboard')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Student Dashboard</h6></div>
    <div class="card-body">
        <p class="mb-0">Welcome, {{ Auth::user()->name }}. Manage all student modules from here.</p>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.courses.index') }}">Courses</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.assignments.index') }}">Assignments</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.quizzes.index') }}">Quizzes</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.attendance.index') }}">Attendance</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.reports.index') }}">Reports</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.extra_details.index') }}">Extra Details</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('student.help_support.index') }}">Help & Support</a></div>
</div>
@endsection
