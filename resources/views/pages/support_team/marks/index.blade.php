@extends('layouts.master')
@section('page_title', 'Manage Exam Marks')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-books text-primary me-2"></i>
            Manage Exam Marks
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Selection Component -->
        <div class="marks-selector-wrapper">
            @include('pages.support_team.marks.selector')
        </div>
        
        <!-- Quick Tips Card -->
        <div class="alert alert-info bg-info bg-opacity-10 border-info border-start border-4 mt-4 mb-0" role="alert">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <i class="icon-info3 text-info fs-4"></i>
                </div>
                <div>
                    <h6 class="alert-heading fw-semibold mb-2">Quick Tips:</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-1">
                            <i class="icon-checkmark-circle text-success me-2"></i>
                            Select an exam, class, and section to manage marks
                        </li>
                        <li class="mb-1">
                            <i class="icon-checkmark-circle text-success me-2"></i>
                            You can enter marks for all subjects at once
                        </li>
                        <li class="mb-1">
                            <i class="icon-checkmark-circle text-success me-2"></i>
                            Marks are automatically saved as you enter them
                        </li>
                        <li>
                            <i class="icon-checkmark-circle text-success me-2"></i>
                            Use tab key to quickly navigate between input fields
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Marks Selector Styling */
.marks-selector-wrapper {
    background: #f8f9fa;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid rgba(0,0,0,0.05);
}

/* Alert/Card Styling */
.alert-info {
    background: rgba(23, 162, 184, 0.05);
    border-radius: 0.75rem;
    padding: 1.25rem;
}

.alert-info .list-unstyled li {
    display: flex;
    align-items: center;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1.25rem !important;
    }
    
    .marks-selector-wrapper {
        padding: 1rem;
        border-radius: 0.75rem;
    }
    
    .alert-info {
        padding: 1rem;
        margin-top: 1.5rem !important;
    }
    
    .alert-info ul {
        margin-top: 0.5rem;
    }
    
    .alert-info li {
        font-size: 0.813rem;
        margin-bottom: 0.5rem !important;
    }
    
    .alert-info .me-3 {
        margin-right: 0.75rem !important;
    }
    
    .alert-info i.icon-info3 {
        font-size: 1.5rem !important;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        padding: 1rem !important;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .marks-selector-wrapper {
        padding: 0.75rem;
    }
    
    .alert-info {
        padding: 0.875rem;
    }
    
    .alert-info h6 {
        font-size: 0.95rem;
        margin-bottom: 0.5rem !important;
    }
    
    .alert-info li {
        font-size: 0.75rem;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.5s ease forwards;
}

/* Hover Effects */
.marks-selector-wrapper:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

/* Loading State for Selector */
.marks-selector-wrapper.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.marks-selector-wrapper.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2rem;
    height: 2rem;
    margin-top: -1rem;
    margin-left: -1rem;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Print Styles */
@media print {
    .navbar, .card-header button, .alert-info {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state when form is submitted
    const selectorForm = document.querySelector('form[action*="marks"]');
    if (selectorForm) {
        selectorForm.addEventListener('submit', function(e) {
            const wrapper = document.querySelector('.marks-selector-wrapper');
            if (wrapper) {
                wrapper.classList.add('loading');
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            }
        });
    }
    
    // Initialize any tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Auto-focus first select on page load
    setTimeout(function() {
        const firstSelect = document.querySelector('.select-search:first-child');
        if (firstSelect && typeof $(firstSelect).select2 !== 'undefined') {
            $(firstSelect).select2('open');
        }
    }, 500);
    
    // Responsive adjustments for select2 dropdowns
    function adjustSelect2ForMobile() {
        if (window.innerWidth < 768) {
            $('.select2-container').css('width', '100%');
        }
    }
    
    adjustSelect2ForMobile();
    
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(adjustSelect2ForMobile, 250);
    });
});

// Optional: Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + E to focus on exam select
    if (e.altKey && e.key === 'e') {
        e.preventDefault();
        const examSelect = document.querySelector('select[name="exam_id"]');
        if (examSelect && typeof $(examSelect).select2 !== 'undefined') {
            $(examSelect).select2('open');
        }
    }
    
    // Alt + C to focus on class select
    if (e.altKey && e.key === 'c') {
        e.preventDefault();
        const classSelect = document.querySelector('select[name="my_class_id"]');
        if (classSelect && typeof $(classSelect).select2 !== 'undefined') {
            $(classSelect).select2('open');
        }
    }
    
    // Alt + S to focus on section select
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        const sectionSelect = document.querySelector('select[name="section_id"]');
        if (sectionSelect && typeof $(sectionSelect).select2 !== 'undefined') {
            $(sectionSelect).select2('open');
        }
    }
    
    // Alt + Enter to submit form
    if (e.altKey && e.key === 'Enter') {
        e.preventDefault();
        const form = document.querySelector('form[action*="marks"]');
        if (form) {
            form.dispatchEvent(new Event('submit'));
        }
    }
});
</script>

<!-- Additional CSS for better Select2 integration -->
<style>
/* Select2 Custom Styling */
.select2-container--default .select2-selection--single {
    border-radius: 0.5rem !important;
    border: 1px solid #dee2e6 !important;
    height: 42px !important;
    transition: all 0.2s ease;
}

.select2-container--default .select2-selection--single:hover {
    border-color: #007bff !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding-left: 1rem !important;
    color: #495057 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.select2-dropdown {
    border-radius: 0.5rem !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    overflow: hidden;
}

/* Form Group Spacing */
.form-group {
    margin-bottom: 1.5rem;
}

@media (max-width: 767.98px) {
    .form-group {
        margin-bottom: 1rem;
    }
}

/* Label Styling */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

/* Required Field Indicator */
.text-danger {
    font-size: 1.2em;
    line-height: 1;
}

/* Button Styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Success Animation for Form Submission */
@keyframes submitSuccess {
    0% { transform: scale(1); }
    50% { transform: scale(0.95); }
    100% { transform: scale(1); }
}

.btn-primary:active {
    animation: submitSuccess 0.2s ease;
}
</style>
@endsection