@extends('layouts.master')
@section('page_title', 'Add Assignment')
@section('content')
<div class="card"><div class="card-body">
<form action="{{ route('teacher.assignments.store') }}" method="post">
    @csrf
    <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" required></div>
    <div class="form-group"><label>Course</label><select name="course_id" class="form-control" required>@foreach($courses as $course)<option value="{{ $course->id }}">{{ $course->title }}</option>@endforeach</select></div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
    <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control"></div>
    <button class="btn btn-primary">Create</button>
</form>
</div></div>
@endsection
