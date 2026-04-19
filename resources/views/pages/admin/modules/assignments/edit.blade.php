@extends('layouts.master')
@section('page_title', 'Edit Teacher Assignment')
@section('content')
<div class="card"><div class="card-header"><h6>Edit Teacher Assignment</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.assign-teachers.update', $record) }}">@csrf @method('PUT') @include('pages.admin.modules.assignments.form', ['record' => $record])</form></div></div>
@endsection
