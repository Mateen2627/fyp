@if(!$class_assignment_enabled)
    <div class="alert-info-modern">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Class Assignment Disabled</strong>
            <p>Class-wise assignment is disabled because the latest migration has not been run yet. You can still assign courses to teachers.</p>
        </div>
    </div>
@endif

@if($courses->isEmpty())
    <div class="alert-warning-modern">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>No Courses Available</strong>
            <p>Please create a course first from <a href="{{ route('admin.courses.create') }}">Manage Courses</a>.</p>
        </div>
    </div>
@endif

@if($teachers->isEmpty())
    <div class="alert-warning-modern">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>No Teachers Found</strong>
            <p>Please create a user with role <strong>Teacher</strong> from <a href="{{ route('admin.users.create') }}">Manage Users</a>.</p>
        </div>
    </div>
@endif

<div class="form-grid">
    @if($class_assignment_enabled)
        <!-- Class Selection (Optional) -->
        <div class="form-group-modern">
            <label class="form-label-modern">
                <i class="fas fa-chalkboard"></i> Class (Optional)
            </label>
            <select name="my_class_id" class="form-control-modern">
                <option value="">— All / Not specific to class —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ (string) old('my_class_id', $record->my_class_id ?? '') === (string) $class->id ? 'selected' : '' }}>
                        <i class="fas fa-school"></i> {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <div class="helper-text">
                <i class="fas fa-info-circle"></i> Leave empty to assign for all classes
            </div>
        </div>
    @endif

    <!-- Course Selection -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-book-open"></i> Course <span class="required">*</span>
        </label>
        <select name="course_id" class="form-control-modern" required {{ $courses->isEmpty() ? 'disabled' : '' }}>
            <option value="">— Select Course —</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ (string) old('course_id', $record->course_id ?? '') === (string) $course->id ? 'selected' : '' }}>
                    <i class="fas fa-book"></i> {{ $course->title }} <span class="option-code">({{ $course->code }})</span>
                </option>
            @endforeach
        </select>
        @if($courses->isEmpty())
            <div class="helper-text text-danger">
                <i class="fas fa-exclamation-circle"></i> No courses available. Please add a course first.
            </div>
        @else
            <div class="helper-text">
                <i class="fas fa-info-circle"></i> Select the course to assign to teacher
            </div>
        @endif
    </div>

    <!-- Teacher Selection -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-chalkboard-user"></i> Teacher <span class="required">*</span>
        </label>
        <select name="teacher_id" class="form-control-modern" required {{ $teachers->isEmpty() ? 'disabled' : '' }}>
            <option value="">— Select Teacher —</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ (string) old('teacher_id', $record->teacher_id ?? '') === (string) $teacher->id ? 'selected' : '' }}>
                    <i class="fas fa-user-graduate"></i> {{ $teacher->name }} <span class="option-email">({{ $teacher->email }})</span>
                </option>
            @endforeach
        </select>
        @if($teachers->isEmpty())
            <div class="helper-text text-danger">
                <i class="fas fa-exclamation-circle"></i> No teachers available. Please add a teacher user first.
            </div>
        @else
            <div class="helper-text">
                <i class="fas fa-info-circle"></i> Select the teacher to assign to this course
            </div>
        @endif
    </div>

    <!-- Notes (Full Width) -->
    <div class="form-group-modern full-width">
        <label class="form-label-modern">
            <i class="fas fa-pen"></i> Additional Notes
        </label>
        <textarea name="notes" class="form-control-modern" placeholder="Enter any additional notes or instructions for this assignment...">{{ old('notes', $record->notes ?? '') }}</textarea>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Optional. Add any special remarks about this assignment
        </div>
    </div>
</div>

<style>
    /* Alert Styles */
    .alert-info-modern {
        background: #eef2ff;
        border-left: 4px solid #4f46e5;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .alert-info-modern i {
        font-size: 20px;
        color: #4f46e5;
    }
    
    .alert-info-modern strong {
        display: block;
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .alert-info-modern p {
        margin: 0;
        font-size: 13px;
        color: #475569;
    }
    
    .alert-warning-modern {
        background: #fefce8;
        border-left: 4px solid #eab308;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .alert-warning-modern i {
        font-size: 20px;
        color: #eab308;
    }
    
    .alert-warning-modern strong {
        display: block;
        font-size: 14px;
        color: #854d0e;
        margin-bottom: 4px;
    }
    
    .alert-warning-modern p {
        margin: 0;
        font-size: 13px;
        color: #854d0e;
    }
    
    .alert-warning-modern a {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 600;
    }
    
    .alert-warning-modern a:hover {
        text-decoration: underline;
    }
    
    /* Option Styling */
    .option-code, .option-email {
        font-size: 11px;
        opacity: 0.7;
        margin-left: 4px;
    }
    
    .text-danger {
        color: #dc2626;
    }
    
    /* Select option icon styling */
    select.form-control-modern option i {
        margin-right: 8px;
    }
</style>