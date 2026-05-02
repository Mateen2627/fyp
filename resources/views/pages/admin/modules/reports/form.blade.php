<div class="form-grid">
    <!-- Report Title -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-heading"></i> Report Title <span class="required">*</span>
        </label>
        <input type="text" name="title" class="form-control-modern" value="{{ old('title', $record->title ?? '') }}" placeholder="e.g., Annual Academic Performance Report" required>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Enter a descriptive title for this report
        </div>
    </div>

    <!-- Report Category -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-folder"></i> Category
        </label>
        <input type="text" name="category" class="form-control-modern" value="{{ old('category', $record->category ?? '') }}" placeholder="e.g., Academic, Financial, Attendance">
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Categorize the report for easy filtering
        </div>
    </div>

    <!-- Report Status -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-flag-checkered"></i> Status <span class="required">*</span>
        </label>
        <select name="status" class="form-control-modern" required>
            <option value="">— Select Status —</option>
            @foreach(['draft','published','archived'] as $status)
                <option value="{{ $status }}" {{ old('status', $record->status ?? 'draft') === $status ? 'selected' : '' }}>
                    @if($status == 'draft')
                        <i class="fas fa-pen"></i> Draft
                    @elseif($status == 'published')
                        <i class="fas fa-check-circle"></i> Published
                    @else
                        <i class="fas fa-archive"></i> Archived
                    @endif
                </option>
            @endforeach
        </select>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Draft: Not visible to users | Published: Visible to all | Archived: Hidden but saved
        </div>
    </div>

    <!-- Report Content (Full Width) -->
    <div class="form-group-modern full-width">
        <label class="form-label-modern">
            <i class="fas fa-file-alt"></i> Report Content <span class="required">*</span>
        </label>
        <textarea name="content" class="form-control-modern" rows="8" placeholder="Write the detailed content of your report here...">{{ old('content', $record->content ?? '') }}</textarea>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Provide detailed information, data, and insights in this report
        </div>
    </div>
</div>

<style>
    /* Additional styling for select options */
    select.form-control-modern option {
        padding: 10px;
    }
    
    /* Full width field */
    .full-width {
        grid-column: 1 / -1;
    }
    
    /* Form grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }
    
    /* Form group modern */
    .form-group-modern {
        margin-bottom: 0;
    }
    
    .form-label-modern {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }
    
    .form-label-modern .required {
        color: var(--danger);
        margin-left: 3px;
    }
    
    .form-label-modern i {
        margin-right: 6px;
        font-size: 11px;
        color: var(--primary);
    }
    
    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control-modern:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    textarea.form-control-modern {
        resize: vertical;
        font-family: inherit;
    }
    
    .helper-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }
    
    .helper-text i {
        font-size: 10px;
        margin-right: 4px;
    }
</style>