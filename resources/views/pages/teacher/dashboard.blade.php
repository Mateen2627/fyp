@extends('layouts.master')
@section('page_title', 'Teacher Dashboard')
@section('content')
<div class="row">
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('teacher.courses.index') }}">My Courses</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('teacher.classes.index') }}">My Classes & Timetable</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('teacher.assignments.index') }}">Assignments (Create/Review/Mark)</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('teacher.quizzes.index') }}">Quizzes (Create/Review/Mark)</a></div>
    <div class="col-md-4 mb-3"><a class="card card-body text-dark" href="{{ route('teacher.attendance.index') }}">Student Attendance</a></div>
</div>
@endsection
