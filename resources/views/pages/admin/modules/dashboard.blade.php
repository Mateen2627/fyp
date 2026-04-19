@extends('layouts.master')
@section('page_title', 'Admin Module Dashboard')
@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-1">Welcome, {{ Auth::user()->name }}</h5>
            <p class="text-muted mb-0">Here is the latest institutional overview with role-wide operational stats.</p>
        </div>
    </div>

    <div class="row">
        @foreach($stats as $label => $count)
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 text-capitalize">{{ str_replace('_', ' ', $label) }}</p>
                        <h3 class="mb-0">{{ $count }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-2">
        <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('admin.users.index') }}">Users</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('admin.classes.index') }}">Classes</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('admin.courses.index') }}">Courses</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('admin.assign-teachers.index') }}">Assign Teachers</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('admin.reports.index') }}">Reports</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-dark shadow-sm border-0" href="{{ route('admin.system-settings.index') }}">System Settings</a></div>
    </div>
@endsection
