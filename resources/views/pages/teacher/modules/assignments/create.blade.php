@extends('layouts.master')
@section('page_title', 'Add Assignment')
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
    
    select.form-control-modern {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
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
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-plus-circle"></i>
        <h6 class="mb-0">Add New Assignment</h6>
    </div>
    <div class="card-body-modern">
        <form enctype="multipart/form-data" action="{{ route('teacher.assignments.store') }}" method="post" id="createAssignmentForm">
            @csrf
            
            <div class="form-grid">
                <!-- Assignment Title -->
                <div class="form-group-modern full-width">
                    <label class="form-label-modern">
                        <i class="fas fa-heading"></i> Assignment Title <span class="required">*</span>
                    </label>
                    <input type="text" name="title" class="form-control-modern" placeholder="e.g., Mathematics Homework - Chapter 5" required>
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Enter a clear and descriptive title for this assignment
                    </div>
                </div>

                <!-- Assign to Class + Course -->
                <div class="form-group-modern full-width">
                    <label class="form-label-modern">
                        <i class="fas fa-chalkboard"></i> Assign to Class & Course <span class="required">*</span>
                    </label>
                    <select class="form-control-modern" id="pair_select" required>
                        <option value="">— Select Class and Course —</option>
                        @foreach($pairs as $pair)
                            <option value="{{ $pair->course_id }}|{{ $pair->my_class_id }}">
                                <i class="fas fa-school"></i> {{ optional($pair->my_class)->name ?: 'Class '.$pair->my_class_id }} — 
                                <i class="fas fa-book"></i> {{ optional($pair->course)->title ?: 'Course '.$pair->course_id }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="course_id" id="course_id">
                    <input type="hidden" name="my_class_id" id="my_class_id">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Select the class and course for this assignment
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group-modern full-width">
                    <label class="form-label-modern">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea name="description" class="form-control-modern" rows="4" placeholder="Provide detailed instructions, guidelines, or requirements for this assignment..."></textarea>
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Explain what students need to do for this assignment
                    </div>
                </div>

                <!-- Assignment PDF -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-file-pdf"></i> Assignment PDF
                    </label>
                    <input type="file" name="attachment" class="form-control-modern" accept="application/pdf">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Upload assignment as PDF (max size: 5MB)
                    </div>
                </div>

                <!-- Due Date -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Due Date
                    </label>
                    <input type="date" name="due_date" class="form-control-modern">
                    <div class="helper-text">
                        <i class="fas fa-info-circle"></i> Set the deadline for assignment submission
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('teacher.assignments.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i> Create Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Pair selection handler
    document.getElementById('pair_select').addEventListener('change', function(){
        const [courseId, classId] = (this.value || '|').split('|');
        document.getElementById('course_id').value = courseId || '';
        document.getElementById('my_class_id').value = classId || '';
    });
    
    // Form submit loading state
    const form = document.getElementById('createAssignmentForm');
    const submitBtn = document.querySelector('.btn-primary-modern');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Creating...';
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = originalText;
        });
    }
    
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