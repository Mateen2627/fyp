@extends('layouts.master')
@section('page_title', 'Edit Course')
@section('content')
<div class="card"><div class="card-header"><h6>Edit Course</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.courses.update', $record) }}">@csrf @method('PUT') @include('pages.admin.modules.courses.form', ['record' => $record])</form></div></div>
@endsection
