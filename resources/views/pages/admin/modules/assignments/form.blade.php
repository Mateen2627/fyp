@if(!$class_assignment_enabled)
    <div class="alert alert-info">Class-wise assignment is disabled because the latest migration has not been run yet. You can still assign courses to teachers.</div>
@endif

@if($courses->isEmpty())
    <div class="alert alert-warning">No courses available yet. Please create a course first from <a href="{{ route('admin.courses.create') }}">Manage Courses</a>.</div>
@endif

@if($teachers->isEmpty())
    <div class="alert alert-warning">No teachers found. Please create a user with role <strong>teacher</strong> from <a href="{{ route('admin.users.create') }}">Manage Users</a>.</div>
@endif

@if($class_assignment_enabled)
    <div class="form-group">
        <label>Class (Optional)</label>
        <select name="my_class_id" class="form-control">
            <option value="">All / Not specific to class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ (string) old('my_class_id', $record->my_class_id ?? '') === (string) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label>Course</label>
    <select name="course_id" class="form-control" required {{ $courses->isEmpty() ? 'disabled' : '' }}>
        <option value="">Select course</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ (string) old('course_id', $record->course_id ?? '') === (string) $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Teacher</label>
    <select name="teacher_id" class="form-control" required {{ $teachers->isEmpty() ? 'disabled' : '' }}>
        <option value="">Select teacher</option>
        @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{ (string) old('teacher_id', $record->teacher_id ?? '') === (string) $teacher->id ? 'selected' : '' }}>{{ $teacher->name }} ({{ $teacher->email }})</option>
        @endforeach
    </select>
</div>

<div class="form-group"><label>Notes</label><textarea name="notes" class="form-control">{{ old('notes', $record->notes ?? '') }}</textarea></div>
<button class="btn btn-primary" {{ $courses->isEmpty() || $teachers->isEmpty() ? 'disabled' : '' }}>Save Assignment</button>
<a href="{{ route('admin.assign-teachers.index') }}" class="btn btn-light">Cancel</a>
