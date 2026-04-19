@extends('layouts.master')
@section('page_title', 'Create Course')
@section('content')
<div class="card"><div class="card-header"><h6>Create Course</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.courses.store') }}">@csrf @include('pages.admin.modules.courses.form')</form></div></div>
@endsection
