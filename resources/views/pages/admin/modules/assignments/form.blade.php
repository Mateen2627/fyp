<div class="form-group"><label>Course</label><select name="course_id" class="form-control" required>@foreach($courses as $course)<option value="{{ $course->id }}" {{ old('course_id', $record->course_id ?? '') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>@endforeach</select></div>
<div class="form-group"><label>Teacher</label><select name="teacher_id" class="form-control" required>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}" {{ old('teacher_id', $record->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>@endforeach</select></div>
<div class="form-group"><label>Notes</label><textarea name="notes" class="form-control">{{ old('notes', $record->notes ?? '') }}</textarea></div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.assign-teachers.index') }}" class="btn btn-light">Cancel</a>
