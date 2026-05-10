@extends('layouts.master')
@section('page_title', 'Student Payments')
@section('content')

<!-- Class Selection Card -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="card-title fw-bold mb-0">
            <i class="icon-cash2 text-primary me-2"></i>
            Student Payments
        </h5>
    </div>

    <div class="card-body p-4">
        <form method="post" action="{{ route('payments.select_class') }}" id="classSelectForm">
            @csrf
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="icon-users text-muted me-1"></i>
                                    Select Class <span class="text-danger">*</span>
                                </label>
                                <select required id="my_class_id" name="my_class_id" 
                                        class="form-control select-search" 
                                        data-placeholder="Choose class...">
                                    <option value=""></option>
                                    @foreach($my_classes as $c)
                                        <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} 
                                                value="{{ $c->id }}">
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="icon-paperplane me-2"></i>
                                View Students
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selected)
<!-- Students List Card -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h6 class="fw-bold mb-2 mb-md-0">
            <i class="icon-users text-primary me-2"></i>
            Students in Selected Class
        </h6>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                <i class="icon-users4 me-1"></i>
                Total: {{ $students->count() }} Students
            </span>
        </div>
    </div>

    <div class="card-body p-0 p-md-3">
        <div class="table-responsive">
            <table class="table table-hover datatable-button-html5-columns mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-3 px-md-4" width="60">S/N</th>
                        <th class="px-3 px-md-4">Student</th>
                        <th class="px-3 px-md-4">Admission No.</th>
                        <th class="px-3 px-md-4 text-center">Manage Payments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $s)
                    <tr class="student-row">
                        <td class="px-3 px-md-4 fw-semibold text-center">{{ $loop->iteration }}</td>
                        <td class="px-3 px-md-4">
                            <div class="d-flex align-items-center">
                                <div class="student-avatar me-3">
                                    <img class="rounded-circle border border-2 border-white shadow-sm" 
                                         style="height: 45px; width: 45px; object-fit: cover;" 
                                         src="{{ Qs::getUserPhoto($s->user) }}"
                                         alt="{{ $s->user->name }}"
                                         onerror="this.src='{{ asset('global_assets/images/placeholder.jpg') }}'">
                                </div>
                                <div>
                                    <span class="fw-semibold d-block">{{ $s->user->name }}</span>
                                    <small class="text-muted">{{ $s->user->email ?? 'No email' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 px-md-4">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                <i class="icon-vcard me-1"></i>
                                {{ $s->adm_no }}
                            </span>
                        </td>
                        <td class="px-3 px-md-4 text-center">
                            <div class="dropdown">
                                <button class="btn btn-gradient-danger btn-sm dropdown-toggle px-4" 
                                        type="button" data-toggle="dropdown">
                                    <i class="icon-cash2 me-2"></i>
                                    Manage Payments
                                    <i class="icon-arrow-down5 ms-2"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2" 
                                     style="min-width: 220px;">
                                    <!-- All Payments Link -->
                                    <a href="{{ route('payments.invoice', [Qs::hash($s->user_id)]) }}" 
                                       class="dropdown-item py-2">
                                        <i class="icon-list2 text-primary me-2"></i>
                                        All Payment History
                                    </a>
                                    
                                    @if(Pay::getYears($s->user_id)->filter()->count() > 0)
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-header small text-muted px-3 py-1">
                                            <i class="icon-calendar me-1"></i>
                                            Filter by Year
                                        </div>
                                        @foreach(Pay::getYears($s->user_id) as $py)
                                            @if($py)
                                                <a href="{{ route('payments.invoice', [Qs::hash($s->user_id), $py]) }}" 
                                                   class="dropdown-item py-2">
                                                    <i class="icon-calendar text-info me-2"></i>
                                                    {{ $py }} Session
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Empty State for No Students --}}
@if($students->count() == 0)
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-5 text-center">
        <div class="empty-state">
            <i class="icon-users4 icon-4x text-muted mb-3"></i>
            <h5 class="fw-semibold mb-2">No Students Found</h5>
            <p class="text-muted mb-0">There are no students in the selected class.</p>
            <button class="btn btn-primary mt-3" onclick="history.back()">
                <i class="icon-arrow-left8 me-2"></i>
                Go Back
            </button>
        </div>
    </div>
</div>
@endif

@endif

<style>
/* Form Styling */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.select-search + .select2-container .select2-selection--single {
    border-radius: 0.5rem !important;
    border: 1px solid #dee2e6 !important;
    height: 42px !important;
    transition: all 0.2s ease;
}

.select-search + .select2-container .select2-selection--single:hover {
    border-color: #007bff !important;
}

.select-search + .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding-left: 1rem !important;
}

/* Table Styling */
.table {
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border-top: none;
    white-space: nowrap;
}

.student-row {
    transition: all 0.2s ease;
}

.student-row:hover {
    background: rgba(0,123,255,0.02);
    transform: translateX(2px);
}

/* Student Avatar */
.student-avatar {
    position: relative;
}

.student-avatar img {
    transition: all 0.3s ease;
}

.student-avatar:hover img {
    transform: scale(1.1);
}

/* Badge Styling */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Gradient Button */
.btn-gradient-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    color: white;
    transition: all 0.3s ease;
    font-weight: 500;
    border-radius: 0.5rem;
}

.btn-gradient-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220,53,69,0.4);
    color: white;
}

.btn-gradient-danger:focus {
    box-shadow: 0 0 0 3px rgba(220,53,69,0.3);
}

/* Dropdown Styling */
.dropdown-menu {
    border-radius: 0.75rem;
    min-width: 220px;
    animation: fadeInDown 0.2s ease;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

.dropdown-header {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dropdown-item {
    transition: all 0.2s ease;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

.dropdown-item i {
    width: 1.25rem;
    text-align: center;
}

/* Dropdown Toggle Button */
.dropdown-toggle.btn-sm {
    padding: 0.5rem 1rem;
}

.dropdown-toggle::after {
    vertical-align: middle;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

.empty-state i {
    font-size: 4rem;
    opacity: 0.3;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1.25rem !important;
    }
    
    .table td, .table th {
        padding: 0.75rem !important;
        font-size: 0.875rem;
    }
    
    .student-avatar img {
        height: 35px !important;
        width: 35px !important;
    }
    
    .student-avatar {
        margin-right: 0.75rem !important;
    }
    
    .badge {
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .btn-gradient-danger {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-gradient-danger i {
        margin-right: 0.25rem !important;
    }
    
    .dropdown-menu {
        min-width: 200px;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        padding: 1rem !important;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .table-responsive {
        margin: 0 -1rem;
        width: calc(100% + 2rem);
    }
    
    .student-avatar img {
        height: 30px !important;
        width: 30px !important;
    }
    
    .student-avatar {
        margin-right: 0.5rem !important;
    }
    
    .badge i {
        display: none;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
    }
    
    .btn-gradient-danger {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .btn-gradient-danger i {
        font-size: 0.75rem;
    }
    
    .dropdown-menu {
        min-width: 180px;
    }
    
    .dropdown-item {
        font-size: 0.8125rem;
        padding: 0.4rem 0.75rem;
    }
    
    .empty-state {
        padding: 1rem;
    }
    
    .empty-state i {
        font-size: 3rem;
    }
    
    .empty-state h5 {
        font-size: 1rem;
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

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.5s ease forwards;
}

/* Loading State */
.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Print Styles */
@media print {
    .navbar, .card-header button, .dropdown, .btn-primary {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 10pt;
    }
    
    .student-avatar img {
        border: 1px solid #ddd !important;
    }
}

/* Select2 Dropdown Styling */
.select2-dropdown {
    border-radius: 0.5rem !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    overflow: hidden;
}

.select2-results__option--highlighted[aria-selected] {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select-search').select2({
            width: '100%',
            allowClear: true,
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option';
            }
        });
    }
    
    // Form submission loading state
    const classSelectForm = document.getElementById('classSelectForm');
    if (classSelectForm) {
        classSelectForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            }
        });
    }
    
    // Add tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Close dropdown when clicking outside (improved mobile behavior)
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').attr('aria-expanded', 'false');
        }
    });
});

// Keyboard shortcut: Alt + C to focus on class select
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key === 'c') {
        e.preventDefault();
        const classSelect = $('#my_class_id');
        if (classSelect.length) {
            classSelect.select2('open');
        }
    }
    
    // Alt + Enter to submit form
    if (e.altKey && e.key === 'Enter') {
        e.preventDefault();
        const form = document.getElementById('classSelectForm');
        if (form && form.querySelector('select').value) {
            form.dispatchEvent(new Event('submit'));
        }
    }
});
</script>
@endsection