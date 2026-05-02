@php($selected = collect(old('student_record_ids', isset($record) ? $record->student_record->pluck('id')->all() : []))->map(fn($v) => (int)$v)->all())

<div class="form-grid">
    <!-- Class Name -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-tag"></i> Class Name <span class="required">*</span>
        </label>
        <input type="text" name="name" class="form-control-modern" value="{{ old('name', $record->name ?? '') }}" placeholder="e.g., Grade 10 - Section A" required>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Enter a unique name for this class
        </div>
    </div>

    <!-- Class Type -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-layer-group"></i> Class Type
        </label>
        <select name="class_type_id" class="form-control-modern">
            <option value="">— Select Class Type —</option>
            @foreach($classTypes as $type)
                <option value="{{ $type->id }}" {{ (string)old('class_type_id', $record->class_type_id ?? '') === (string)$type->id ? 'selected' : '' }}>
                    <i class="fas fa-school"></i> {{ $type->name }}
                </option>
            @endforeach
        </select>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Select the type/category of this class
        </div>
    </div>

    <!-- Assign Students (Full Width) -->
    <div class="form-group-modern full-width">
        <label class="form-label-modern">
            <i class="fas fa-users"></i> Assign Students
        </label>
        <div class="multiselect-wrapper">
            <select name="student_record_ids[]" class="form-control-modern multiselect-student" multiple size="8">
                @foreach($studentRecords as $sr)
                    <option value="{{ $sr->id }}" {{ in_array($sr->id, $selected) ? 'selected' : '' }}>
                        📚 #{{ $sr->id }} - {{ optional($sr->user)->name ?: 'Student '.$sr->user_id }} 
                        <span class="option-badge">(Current Class ID: {{ $sr->my_class_id }})</span>
                    </option>
                @endforeach
            </select>
        </div>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Hold <strong>Ctrl (Windows)</strong> or <strong>Cmd (Mac)</strong> to select multiple students
        </div>
        <div class="selected-count" id="selectedCount">
            <i class="fas fa-check-circle"></i> <span>0</span> student(s) selected
        </div>
    </div>
</div>

<style>
    /* Multiselect Styling */
    .multiselect-wrapper {
        position: relative;
    }
    
    .multiselect-student {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px;
        font-size: 13px;
    }
    
    .multiselect-student option {
        padding: 8px 12px;
        margin: 2px 0;
        border-radius: 6px;
        transition: all 0.2s;
        background: white;
        color: var(--dark);
    }
    
    .multiselect-student option:hover {
        background: var(--light);
    }
    
    .multiselect-student option:checked {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }
    
    .option-badge {
        font-size: 11px;
        opacity: 0.7;
        margin-left: 8px;
    }
    
    .multiselect-student option:checked .option-badge {
        color: rgba(255,255,255,0.8);
    }
    
    .selected-count {
        margin-top: 12px;
        padding: 8px 12px;
        background: var(--light);
        border-radius: 8px;
        font-size: 12px;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .selected-count i {
        font-size: 14px;
    }
    
    /* Full width */
    .full-width {
        grid-column: 1 / -1;
    }
</style>

<script>
    // Update selected count display
    document.addEventListener('DOMContentLoaded', function() {
        const multiselect = document.querySelector('.multiselect-student');
        const selectedCountSpan = document.querySelector('#selectedCount span');
        
        if (multiselect && selectedCountSpan) {
            function updateSelectedCount() {
                const selectedOptions = Array.from(multiselect.selectedOptions);
                selectedCountSpan.textContent = selectedOptions.length;
            }
            
            updateSelectedCount();
            multiselect.addEventListener('change', updateSelectedCount);
        }
    });
</script>