@extends('layouts.master')
@section('page_title', $record ? 'Edit Teaching Assignment' : 'Assign Teacher')
@section('content')
<div class="card"><div class="card-header"><h5 class="mb-0">{{ $record ? 'Edit Teaching Assignment' : 'Assign Teacher' }}</h5></div><div class="card-body">
    <form method="post" action="{{ $record ? route('hod.assign-teachers.update', $record) : route('hod.assign-teachers.store') }}">
        @csrf @if($record) @method('PUT') @endif
        <div class="form-group"><label for="semester">Department / Semester</label><select id="semester" name="my_class_id" class="form-control" required>
            <option value="">Select semester</option>@foreach($classes as $class)<option data-department="{{ $class->class_type_id }}" value="{{ $class->id }}" {{ old('my_class_id', optional($record)->my_class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>@endforeach
        </select></div>
        <div class="form-group"><label for="subject">Subject</label><select id="subject" name="course_id" class="form-control" required>
            <option value="">Select subject</option>@foreach($courses as $course)<option data-class="{{ $course->my_class_id }}" value="{{ $course->id }}" {{ old('course_id', optional($record)->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }} ({{ $course->code }})</option>@endforeach
        </select></div>
        <div class="form-group"><label for="section">Section</label><select id="section" name="section_id" class="form-control">
            <option value="">All sections</option>@foreach($classes as $class) @foreach($class->section as $section)<option data-class="{{ $class->id }}" value="{{ $section->id }}" {{ old('section_id', optional($record)->section_id) == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>@endforeach @endforeach
        </select></div>
        <div class="form-group"><label for="teacher">Teacher</label><select id="teacher" name="teacher_id" class="form-control" required>
            <option value="">Select teacher</option>@foreach($teachers as $teacher)<option data-departments="{{ $teacher->departmentRoles->pluck('department_id')->implode(',') }}" value="{{ $teacher->id }}" {{ old('teacher_id', optional($record)->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>@endforeach
        </select></div>
        <div class="form-group"><label for="notes">Notes</label><textarea id="notes" name="notes" class="form-control" maxlength="2000">{{ old('notes', optional($record)->notes) }}</textarea></div>
        <button class="btn btn-primary">Save Assignment</button> <a class="btn btn-light" href="{{ route('hod.assign-teachers.index') }}">Cancel</a>
    </form>
</div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const semester = document.getElementById('semester');
    function filter() {
        const department = semester.selectedOptions[0]?.dataset.department;
        ['subject', 'section', 'teacher'].forEach(function (id) {
            const select = document.getElementById(id);
            Array.from(select.options).forEach(function (option) {
                const allowed = !option.value || (id === 'teacher' ? (option.dataset.departments || '').split(',').includes(department) : option.dataset.class === semester.value);
                option.hidden = !allowed; option.disabled = !allowed;
            });
            if (select.selectedOptions[0]?.disabled) select.value = '';
        });
    }
    semester.addEventListener('change', filter); filter();
});
</script>
@endsection
