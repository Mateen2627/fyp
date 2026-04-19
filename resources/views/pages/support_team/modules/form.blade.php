@php
    $entity = $record ?? null;
@endphp

@if(in_array($module, ['courses', 'assignments', 'quizzes', 'student_reports', 'announcements']))
    <div class="form-group">
        <label>Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', optional($entity)->title) }}" required>
    </div>
@endif

@if($module === 'courses')
    <div class="form-group"><label>Code</label><input type="text" name="code" class="form-control" value="{{ old('code', optional($entity)->code) }}"></div>
    <div class="form-group"><label>Class ID</label><input type="number" name="my_class_id" class="form-control" value="{{ old('my_class_id', optional($entity)->my_class_id) }}"></div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control">{{ old('description', optional($entity)->description) }}</textarea></div>
@endif

@if(in_array($module, ['assignments', 'quizzes']))
    <div class="form-group"><label>Course ID</label><input type="number" name="course_id" class="form-control" value="{{ old('course_id', optional($entity)->course_id) }}"></div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control">{{ old('description', optional($entity)->description) }}</textarea></div>
@endif

@if($module === 'assignments')
    <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control" value="{{ old('due_date', optional(optional($entity)->due_date)->format('Y-m-d')) }}"></div>
@endif

@if($module === 'quizzes')
    <div class="form-group"><label>Total Marks</label><input type="number" min="0" name="total_marks" class="form-control" value="{{ old('total_marks', optional($entity)->total_marks) }}"></div>
    <div class="form-group"><label>Scheduled At</label><input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', optional(optional($entity)->scheduled_at)->format('Y-m-d\\TH:i')) }}"></div>
@endif

@if($module === 'attendance')
    <div class="form-group"><label>Student Record ID <span class="text-danger">*</span></label><input type="number" name="student_record_id" class="form-control" value="{{ old('student_record_id', optional($entity)->student_record_id) }}" required></div>
    <div class="form-group"><label>Attendance Date <span class="text-danger">*</span></label><input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', optional(optional($entity)->attendance_date)->format('Y-m-d')) }}" required></div>
    <div class="form-group"><label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            @foreach(['present','absent','late','excused'] as $status)
                <option value="{{ $status }}" {{ old('status', optional($entity)->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group"><label>Remarks</label><textarea name="remarks" class="form-control">{{ old('remarks', optional($entity)->remarks) }}</textarea></div>
@endif

@if($module === 'student_reports')
    <div class="form-group"><label>Student Record ID <span class="text-danger">*</span></label><input type="number" name="student_record_id" class="form-control" value="{{ old('student_record_id', optional($entity)->student_record_id) }}" required></div>
    <div class="form-group"><label>Report <span class="text-danger">*</span></label><textarea name="report" class="form-control" required>{{ old('report', optional($entity)->report) }}</textarea></div>
    <div class="form-group"><label>Reported At</label><input type="datetime-local" name="reported_at" class="form-control" value="{{ old('reported_at', optional(optional($entity)->reported_at)->format('Y-m-d\\TH:i')) }}"></div>
@endif

@if($module === 'announcements')
    <div class="form-group"><label>Body <span class="text-danger">*</span></label><textarea name="body" class="form-control" required>{{ old('body', optional($entity)->body) }}</textarea></div>
    <div class="form-group"><label>Publish Date</label><input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional(optional($entity)->published_at)->format('Y-m-d\\TH:i')) }}"></div>
    <div class="form-group form-check"><input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', optional($entity)->is_active) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active</label></div>
@endif
