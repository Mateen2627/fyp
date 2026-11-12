@extends('layouts.master')
@section('page_title', 'Edit Assignment')
@section('content')

<style>
    /* Modern Professional Styling */
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }
    
    /* Main Card */
    .dashboard-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* Card Header */
    .card-header-modern {
        background: white;
        padding: 20px 28px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-modern i {
        font-size: 20px;
        color: var(--primary);
    }
    
    .card-header-modern h6 {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    /* Card Body */
    .card-body-modern {
        padding: 28px;
    }
    
    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }
    
    /* Form Groups */
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
        min-height: 100px;
        font-family: inherit;
    }
    
    input[type="file"].form-control-modern {
        padding: 8px 12px;
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
    
    /* Full Width Fields */
    .full-width {
        grid-column: 1 / -1;
    }
    
    /* Info Banner */
    .info-banner {
        background: #eef2ff;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 3px solid var(--primary);
    }
    
    .info-banner i {
        font-size: 18px;
        color: var(--primary);
    }
    
    .info-banner p {
        margin: 0;
        font-size: 13px;
        color: var(--dark);
    }
    
    /* Download Button */
    .btn-download-modern {
        background: #eef2ff;
        border: 1px solid var(--border);
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 13px;
        color: var(--primary);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin-bottom: 20px;
    }
    
    .btn-download-modern:hover {
        background: #e0e7ff;
        transform: translateY(-1px);
    }
    
    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 10px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .btn-secondary-modern {
        background: #f1f5f9;
        border: none;
        padding: 10px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        color: var(--gray);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-secondary-modern:hover {
        background: #e2e8f0;
        color: var(--dark);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .card-body-modern {
            padding: 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-primary-modern, .btn-secondary-modern {
            width: 100%;
            justify-content: center;
        }
        
        .info-banner {
            padding: 12px 16px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-edit"></i>
        <h6 class="mb-0">Edit Assignment</h6>
    </div>
    <div class="card-body-modern">
        <!-- Edit Mode Info Banner -->
        <div class="info-banner">
            <i class="fas fa-info-circle"></i>
            <p>You are editing assignment: <strong>{{ $assignment->title }}</strong>. Update the information below and click Update to save changes.</p>
        </div>
        
        <form enctype="multipart/form-data" action="{{ route('teacher.assignments.update', $assignment->id) }}" method="post" id="editAssignmentForm">
            @csrf 
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group-modern"><label class="form-label-modern">Total Marks</label><input name="total_marks" type="number" min="1" max="999" class="form-control-modern" required value="{{ old('total_marks', isset($assignment) ? $assignment->total_marks : 100) }}"></div>
                <!-- Assignment Title -->
                <div class="form-group-modern full-width">
                    <label class="form-label-modern">
                        <i class="fas fa-heading"></i> Assignment Title <span class="required">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" class="form-control-modern" placeholder="e.g., Mathematics Homework - Chapter 5" required>
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Enter a clear and descriptive title for this assignment
                    </div>
                </div>

                <!-- Current File Download -->
                @if($assignment->attachment_path)
                    <div class="form-group-modern full-width">
                        <label class="form-label-modern">
                            <i class="fas fa-paperclip"></i> Current Attachment
                        </label>
                        <a href="{{ route('teacher.assignments.download', $assignment->id) }}" class="btn-download-modern">
                            <i class="fas fa-download"></i> Download Current File
                        </a>
                    </div>
                @endif

                <!-- Description -->
                <div class="form-group-modern full-width">
                    <label class="form-label-modern">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea name="description" class="form-control-modern" rows="4" placeholder="Provide detailed instructions, guidelines, or requirements for this assignment...">{{ old('description', $assignment->description) }}</textarea>
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Explain what students need to do for this assignment
                    </div>
                </div>

                <!-- Due Date -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Due Date
                    </label>
                    <input type="date" name="due_date" value="{{ old('due_date', optional($assignment->due_date)->format('Y-m-d')) }}" class="form-control-modern">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Set the deadline for assignment submission
                    </div>
                </div>

                <!-- Assignment PDF (Replace) -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-file-pdf"></i> Replace PDF (Optional)
                    </label>
                    <input type="file" name="attachment" class="form-control-modern" accept="application/pdf">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Upload a new PDF to replace the existing file (max size: 5MB)
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('teacher.assignments.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i> Update Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Form submit loading state
    const form = document.getElementById('editAssignmentForm');
    const submitBtn = document.querySelector('.btn-primary-modern');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = originalText;
        });
    }
    
    // Warn before leaving unsaved changes
    let formChanged = false;
    const formInputs = document.querySelectorAll('#editAssignmentForm input, #editAssignmentForm textarea, #editAssignmentForm select');
    
    formInputs.forEach(input => {
        input.addEventListener('change', function() {
            formChanged = true;
        });
        input.addEventListener('input', function() {
            formChanged = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    // Loading spinner style
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>

@endsection