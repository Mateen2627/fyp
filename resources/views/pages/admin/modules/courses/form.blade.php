<div class="form-grid">
    <!-- Course Title -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-heading"></i> Course Title <span class="required">*</span>
        </label>
        <input type="text" maxlength="100" name="title" class="form-control-modern" value="{{ old('title', $record->title ?? '') }}" placeholder="e.g., Introduction to Computer Science" required>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Enter the full course name
        </div>
    </div>

    <!-- Course Code -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-barcode"></i> Course Code
        </label>
        <input type="text" maxlength="50" name="code" class="form-control-modern" value="{{ old('code', $record->code ?? '') }}" placeholder="e.g., CS101">
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Unique identifier for the course
        </div>
    </div>

    <!-- Class Selection -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-chalkboard"></i> Department / Semester <span class="required">*</span>
        </label>
        <select name="my_class_id" class="form-control-modern" required>
            <option value="">— Not linked to any class —</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ (string) old('my_class_id', $record->my_class_id ?? '') === (string) $class->id ? 'selected' : '' }}>
                    <i class="fas fa-school"></i> {{ $class->name }}
                </option>
            @endforeach
        </select>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Select the semester that offers this subject
        </div>
    </div>

    <!-- Course Description (Full Width) -->
    <div class="form-group-modern full-width">
        <label class="form-label-modern">
            <i class="fas fa-align-left"></i> Course Description
        </label>
        <textarea name="description" class="form-control-modern" placeholder="Enter detailed course description, objectives, and outcomes...">{{ old('description', $record->description ?? '') }}</textarea>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Provide a comprehensive description of the course
        </div>
    </div>
</div>

<style>
    /* Additional styling for full-width */
    .full-width {
        grid-column: 1 / -1;
    }
    
    /* Select option styling */
    select.form-control-modern option {
        padding: 8px;
    }
</style>