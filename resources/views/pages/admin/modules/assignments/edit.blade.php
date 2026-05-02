@extends('layouts.master')
@section('page_title', 'Edit Teacher Assignment')
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
    
    /* Info Banner for Edit Mode */
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
        <h6 class="mb-0">Edit Teacher Assignment</h6>
    </div>
    <div class="card-body-modern">
        <!-- Edit Mode Info Banner -->
        <div class="info-banner">
            <i class="fas fa-info-circle"></i>
            <p>You are editing assignment for course: <strong>{{ optional($record->course)->title ?? 'N/A' }}</strong>. Update the information below and click Update to save changes.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.assign-teachers.update', $record) }}" id="editAssignmentForm">
            @csrf
            @method('PUT')
            @include('pages.admin.modules.assignments.form', ['record' => $record])
            
            <div class="form-actions">
                <a href="{{ route('admin.assign-teachers.index') }}" class="btn-secondary-modern">
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
    const formInputs = document.querySelectorAll('#editAssignmentForm input, #editAssignmentForm select, #editAssignmentForm textarea');
    
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