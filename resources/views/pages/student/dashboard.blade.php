@extends('layouts.master')
@section('page_title', 'Student Dashboard')
@section('content')

<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Student Dashboard</h6></div>
    <div class="card-body">
        <p class="mb-0">Welcome, {{ Auth::user()->name }}. Track your academics and timetable from here.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3"><a class="card card-body text-dark" href="{{ route('marks.year_select', Qs::hash(Auth::user()->id)) }}">My Marksheet Module</a></div>
    <div class="col-md-6 mb-3"><a class="card card-body text-dark" href="{{ route('tt.index') }}">My Timetable Module</a></div>
</div>
@endsection
