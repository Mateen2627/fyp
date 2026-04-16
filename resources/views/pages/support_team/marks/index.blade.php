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
                            Use <kbd class="bg-light text-dark px-2 py-1 rounded">Tab</kbd> key to quickly navigate between input fields
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="marksToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="icon-checkmark-circle me-2"></i>
                <span id="toastMessage">Marks saved successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
/* Marks Selector Styling */
.marks-selector-wrapper {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
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

/* KBD styling */
kbd {
    font-size: 0.7rem;
    font-family: monospace;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}

/* Toast Container */
.toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
}

.toast {
    min-width: 280px;
    border-radius: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

.toast.show {
    opacity: 1;
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
    
    .toast-container {
        bottom: 10px;
        right: 10px;
        left: 10px;
    }
    
    .toast {
        width: calc(100% - 20px);
        min-width: auto;
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
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
    transform: translateY(-2px);
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
    .navbar, .card-header button, .alert-info, .toast-container {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .marks-selector-wrapper {
        background: none !important;
        padding: 0 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap toast
    if (typeof bootstrap !== 'undefined') {
        const toastEl = document.getElementById('marksToast');
        if (toastEl) {
            window.marksToast = new bootstrap.Toast(toastEl);
        }
    }
    
    // Check for session messages
    @if(session('success'))
        showMarksToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showMarksToast('{{ session('error') }}', 'danger');
    @endif
    
    @if(session('warning'))
        showMarksToast('{{ session('warning') }}', 'warning');
    @endif
    
    @if(session('info'))
        showMarksToast('{{ session('info') }}', 'info');
    @endif
    
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
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                
                // Store original text for potential restore (though form will redirect)
                submitBtn.setAttribute('data-original-text', originalText);
            }
        });
    }
    
    // Initialize any tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Auto-focus first select on page load with better UX
    setTimeout(function() {
        const firstSelect = document.querySelector('.select-search:first-child');
        if (firstSelect && typeof $(firstSelect).select2 !== 'undefined') {
            // Don't auto-open on mobile to prevent keyboard popup
            if (window.innerWidth >= 768) {
                $(firstSelect).select2('open');
            }
        }
    }, 500);
    
    // Responsive adjustments for select2 dropdowns
    function adjustSelect2ForMobile() {
        if (window.innerWidth < 768) {
            $('.select2-container').css('width', '100%');
            // Adjust dropdown placement for mobile
            $('.select2-dropdown').css({
                'position': 'fixed',
                'top': 'auto',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'border-radius': '1rem 1rem 0 0',
                'max-height': '60vh'
            });
        } else {
            $('.select2-container').css('width', '');
            $('.select2-dropdown').css({
                'position': '',
                'top': '',
                'bottom': '',
                'left': '',
                'right': '',
                'border-radius': '',
                'max-height': ''
            });
        }
    }
    
    adjustSelect2ForMobile();
    
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(adjustSelect2ForMobile, 250);
    });
    
    // Add animation to form fields on focus
    const formInputs = document.querySelectorAll('.marks-selector-wrapper input, .marks-selector-wrapper select');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});

// Show toast notification
function showMarksToast(message, type = 'success') {
    const toastEl = document.getElementById('marksToast');
    const toastMessage = document.getElementById('toastMessage');
    
    if (toastEl && toastMessage) {
        toastMessage.innerHTML = message;
        
        // Set toast color based on type
        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
        
        let icon = 'icon-checkmark-circle';
        if (type === 'success') {
            toastEl.classList.add('bg-success');
            icon = 'icon-checkmark-circle';
        } else if (type === 'danger' || type === 'error') {
            toastEl.classList.add('bg-danger');
            icon = 'icon-cross-circle';
        } else if (type === 'warning') {
            toastEl.classList.add('bg-warning');
            icon = 'icon-warning';
        } else if (type === 'info') {
            toastEl.classList.add('bg-info');
            icon = 'icon-info3';
        }
        
        // Update icon
        const iconSpan = toastMessage.parentElement.querySelector('i');
        if (iconSpan) {
            iconSpan.className = `${icon} me-2`;
        }
        
        // Show toast
        if (typeof bootstrap !== 'undefined' && window.marksToast) {
            window.marksToast.show();
        } else {
            toastEl.classList.add('show');
            setTimeout(() => {
                toastEl.classList.remove('show');
            }, 3000);
        }
    }
}

// Enhanced keyboard shortcuts with visual feedback
document.addEventListener('keydown', function(e) {
    // Alt + E to focus on exam select
    if (e.altKey && e.key === 'e') {
        e.preventDefault();
        const examSelect = document.querySelector('select[name="exam_id"]');
        if (examSelect && typeof $(examSelect).select2 !== 'undefined') {
            $(examSelect).select2('open');
            showShortcutFeedback('Exam selector activated');
        }
    }
    
    // Alt + C to focus on class select
    if (e.altKey && e.key === 'c') {
        e.preventDefault();
        const classSelect = document.querySelector('select[name="my_class_id"]');
        if (classSelect && typeof $(classSelect).select2 !== 'undefined') {
            $(classSelect).select2('open');
            showShortcutFeedback('Class selector activated');
        }
    }
    
    // Alt + S to focus on section select
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        const sectionSelect = document.querySelector('select[name="section_id"]');
        if (sectionSelect && typeof $(sectionSelect).select2 !== 'undefined') {
            $(sectionSelect).select2('open');
            showShortcutFeedback('Section selector activated');
        }
    }
    
    // Alt + Enter to submit form
    if (e.altKey && e.key === 'Enter') {
        e.preventDefault();
        const form = document.querySelector('form[action*="marks"]');
        if (form && form.checkValidity()) {
            showShortcutFeedback('Submitting form...');
            form.dispatchEvent(new Event('submit'));
        } else if (form) {
            form.reportValidity();
        }
    }
});

// Show temporary feedback for keyboard shortcuts
let feedbackTimeout;
function showShortcutFeedback(message) {
    // Remove existing feedback if any
    const existingFeedback = document.querySelector('.shortcut-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Create feedback element
    const feedback = document.createElement('div');
    feedback.className = 'shortcut-feedback position-fixed top-50 start-50 translate-middle bg-dark text-white px-3 py-2 rounded shadow-lg';
    feedback.style.zIndex = '9999';
    feedback.style.fontSize = '0.875rem';
    feedback.innerHTML = `<i class="icon-keyboard me-2"></i>${message}`;
    document.body.appendChild(feedback);
    
    // Clear previous timeout
    if (feedbackTimeout) {
        clearTimeout(feedbackTimeout);
    }
    
    // Remove after 1.5 seconds
    feedbackTimeout = setTimeout(() => {
        feedback.style.opacity = '0';
        feedback.style.transition = 'opacity 0.3s ease';
        setTimeout(() => feedback.remove(), 300);
    }, 1500);
}
</script>

<!-- Additional CSS for better Select2 integration and keyboard feedback -->
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

/* Focus animation for form groups */
.form-group.focused .form-label {
    color: #007bff;
}

.form-group.focused .input-group-text,
.form-group.focused .form-control {
    border-color: #007bff;
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
    transition: color 0.2s ease;
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

/* Keyboard shortcut hint styling */
.shortcut-hint {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.75rem;
    z-index: 1040;
    pointer-events: none;
    font-family: monospace;
}

@media (max-width: 767.98px) {
    .shortcut-hint {
        display: none;
    }
}
</style>

<!-- Keyboard Shortcuts Hint (Optional) -->
<div class="shortcut-hint">
    <i class="icon-keyboard me-1"></i>
    Shortcuts: <kbd>Alt+E</kbd> <kbd>Alt+C</kbd> <kbd>Alt+S</kbd> <kbd>Alt+Enter</kbd>
</div>

@endsection