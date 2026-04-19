@extends('layouts.master')
@section('page_title', 'Create Teacher Assignment')
@section('content')
<div class="card"><div class="card-header"><h6>Create Teacher Assignment</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.assign-teachers.store') }}">@csrf @include('pages.admin.modules.assignments.form')</form></div></div>
@endsection
