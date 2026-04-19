@extends('layouts.master')
@section('page_title', 'Add Assignment')
@section('content')
<div class="card"><div class="card-body">
<form enctype="multipart/form-data" action="{{ route('teacher.assignments.store') }}" method="post">
    @csrf
    <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" required></div>
    <div class="form-group"><label>Assign to Class + Course</label>
        <select class="form-control" id="pair_select" required>
            <option value="">Select</option>
            @foreach($pairs as $pair)
                <option value="{{ $pair->course_id }}|{{ $pair->my_class_id }}">{{ optional($pair->my_class)->name ?: 'Class '.$pair->my_class_id }} - {{ optional($pair->course)->title ?: 'Course '.$pair->course_id }}</option>
            @endforeach
        </select>
        <input type="hidden" name="course_id" id="course_id">
        <input type="hidden" name="my_class_id" id="my_class_id">
    </div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
    <div class="form-group"><label>Assignment PDF</label><input type="file" name="attachment" class="form-control" accept="application/pdf"></div>
    <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control"></div>
    <button class="btn btn-primary">Create</button>
</form>
</div></div>
<script>
document.getElementById('pair_select').addEventListener('change', function(){
    const [courseId, classId] = (this.value || '|').split('|');
    document.getElementById('course_id').value = courseId || '';
    document.getElementById('my_class_id').value = classId || '';
});
</script>
@endsection
