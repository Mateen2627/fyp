@extends('layouts.master')
@section('page_title', 'Edit Assignment')
@section('content')
<div class="card"><div class="card-body">
<form action="{{ route('teacher.assignments.update', $assignment->id) }}" method="post">
    @csrf @method('PUT')
    <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $assignment->title) }}" class="form-control" required></div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $assignment->description) }}</textarea></div>
    <div class="form-group"><label>Due Date</label><input type="date" name="due_date" value="{{ old('due_date', optional($assignment->due_date)->format('Y-m-d')) }}" class="form-control"></div>
    <button class="btn btn-primary">Update</button>
</form>
</div></div>
@endsection
