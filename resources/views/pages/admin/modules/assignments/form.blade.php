@if($courses->isEmpty())
    <div class="alert alert-warning">No courses available yet. Please create a course first from <a href="{{ route('admin.courses.create') }}">Manage Courses</a>.</div>
@endif

@if($teachers->isEmpty())
    <div class="alert alert-warning">No teachers found. Please create a user with role <strong>teacher</strong> from <a href="{{ route('admin.users.create') }}">Manage Users</a>.</div>
@endif

<div class="form-group">
    <label>Course</label>
    <select name="course_id" class="form-control" required {{ $courses->isEmpty() ? 'disabled' : '' }}>
        <option value="">Select course</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ old('course_id', $record->course_id ?? '') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Teacher</label>
    <select name="teacher_id" class="form-control" required {{ $teachers->isEmpty() ? 'disabled' : '' }}>
        <option value="">Select teacher</option>
        @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{ old('teacher_id', $record->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }} ({{ $teacher->email }})</option>
        @endforeach
    </select>
</div>
<div class="form-group"><label>Notes</label><textarea name="notes" class="form-control">{{ old('notes', $record->notes ?? '') }}</textarea></div>
<button class="btn btn-primary" {{ $courses->isEmpty() || $teachers->isEmpty() ? 'disabled' : '' }}>Save</button>
<a href="{{ route('admin.assign-teachers.index') }}" class="btn btn-light">Cancel</a>
