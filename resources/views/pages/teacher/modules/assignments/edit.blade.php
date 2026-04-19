@extends('layouts.master')
@section('page_title', 'Edit Assignment')
@section('content')
<div class="card"><div class="card-body">
<form enctype="multipart/form-data" action="{{ route('teacher.assignments.update', $assignment->id) }}" method="post">
    @csrf @method('PUT')
    <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $assignment->title) }}" class="form-control" required></div>
    @if($assignment->attachment_path)
    <a href="{{ route('teacher.assignments.download', $assignment->id) }}" class="btn btn-sm btn-outline-primary mb-2">Download Current File</a>
    @endif
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $assignment->description) }}</textarea></div>
    <div class="form-group"><label>Due Date</label><input type="date" name="due_date" value="{{ old('due_date', optional($assignment->due_date)->format('Y-m-d')) }}" class="form-control"></div>
    <div class="form-group"><label>Assignment PDF</label><input type="file" name="attachment" class="form-control" accept="application/pdf"></div>
    <button class="btn btn-primary">Update</button>
</form>
</div></div>
@endsection
