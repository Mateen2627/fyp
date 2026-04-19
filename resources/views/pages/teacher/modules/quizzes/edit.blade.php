@extends('layouts.master')
@section('page_title', 'Edit Quiz')
@section('content')
<div class="card"><div class="card-body">
<form enctype="multipart/form-data" action="{{ route('teacher.quizzes.update', $quiz->id) }}" method="post">
    @csrf @method('PUT')
    <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="form-control" required></div>
    @if($quiz->attachment_path)
    <a href="{{ route('teacher.quizzes.download', $quiz->id) }}" class="btn btn-sm btn-outline-primary mb-2">Download Current File</a>
    @endif
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $quiz->description) }}</textarea></div>
    <div class="form-group"><label>Total Marks</label><input type="number" min="0" name="total_marks" value="{{ old('total_marks', $quiz->total_marks) }}" class="form-control"></div>
    <div class="form-group"><label>Scheduled At</label><input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($quiz->scheduled_at)->format('Y-m-d\\TH:i')) }}" class="form-control"></div>
    <div class="form-group"><label>Quiz PDF</label><input type="file" name="attachment" class="form-control" accept="application/pdf"></div>
    <button class="btn btn-primary">Update</button>
</form>
</div></div>
@endsection
