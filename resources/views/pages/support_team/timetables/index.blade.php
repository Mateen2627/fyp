@extends('layouts.master')
@section('page_title', 'Manage Timetables')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-calendar text-primary me-2"></i>
            Manage Timetables
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-highlight flex-nowrap flex-sm-wrap overflow-auto pb-2 pb-sm-0" role="tablist">
            @if(Qs::userIsTeamSA())
            <li class="nav-item me-1">
                <a href="#add-tt" class="nav-link active px-3 px-sm-4" data-toggle="tab" role="tab">
                    <i class="icon-plus-circle2 me-1 d-none d-sm-inline"></i>
                    Create Timetable
                </a>
            </li>
            @endif
            <li class="nav-item dropdown ms-auto">
                <a href="#" class="nav-link dropdown-toggle px-3 px-sm-4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-calendar2 me-1"></i>
                    <span class="d-none d-sm-inline">Show Timetables</span>
                    <span class="d-sm-none">Timetables</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                    @foreach($my_classes as $mc)
                        <a href="#ttr{{ $mc->id }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                            <i class="icon-users me-2"></i>
                            {{ $mc->name }}
                            <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                                {{ $tt_records->where('my_class_id', $mc->id)->count() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-4">
            @if(Qs::userIsTeamSA())
            <!-- Create Timetable Tab -->
            <div class="tab-pane fade show active" id="add-tt" role="tabpanel">
                <div class="row justify-content-center justify-content-md-start">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="icon-plus-circle2 text-primary me-2"></i>
                                    Create New Timetable
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form class="ajax-store" method="post" action="{{ route('ttr.store') }}" id="createTimetableForm">
                                    @csrf
                                    
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">
                                            Timetable Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-calendar text-muted"></i>
                                            </span>
                                            <input name="name" value="{{ old('name') }}" required 
                                                   type="text" class="form-control border-start-0 ps-0" 
                                                   placeholder="e.g., First Term Timetable">
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">
                                            Select Class <span class="text-danger">*</span>
                                        </label>
                                        <select required data-placeholder="Select Class" 
                                                class="form-control select-search" name="my_class_id" id="my_class_id">
                                            <option value=""></option>
                                            @foreach($my_classes as $mc)
                                                <option {{ old('my_class_id') == $mc->id ? 'selected' : '' }} 
                                                        value="{{ $mc->id }}">
                                                    {{ $mc->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">
                                            Timetable Type
                                        </label>
                                        <select class="form-control select-search" name="exam_id" id="exam_id">
                                            <option value="">Class Timetable (Regular)</option>
                                            @foreach($exams as $ex)
                                                <option {{ old('exam_id') == $ex->id ? 'selected' : '' }} 
                                                        value="{{ $ex->id }}">
                                                    {{ $ex->name }} (Exam)
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">
                                            <i class="icon-info3 me-1"></i>
                                            Select exam for exam timetable or leave empty for class timetable
                                        </small>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="reset" class="btn btn-light me-2" id="resetFormBtn">
                                            <i class="icon-reset me-1"></i>
                                            Reset
                                        </button>
                                        <button id="ajax-btn" type="submit" class="btn btn-primary px-4">
                                            <i class="icon-paperplane me-2"></i>
                                            Create Timetable
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timetables List Tabs -->
            @foreach($my_classes as $mc)
                <div class="tab-pane fade" id="ttr{{ $mc->id }}" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between">
                            <h6 class="fw-bold mb-2 mb-sm-0">
                                <i class="icon-users text-primary me-2"></i>
                                {{ $mc->name }} - Timetables
                            </h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="icon-calendar me-1"></i>
                                Total: {{ $tt_records->where('my_class_id', $mc->id)->count() }} Timetables
                            </span>
                        </div>
                        
                        <div class="card-body p-0 p-md-3">
                            @if($tt_records->where('my_class_id', $mc->id)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover datatable-button-html5-columns mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 px-md-4" width="50">#</th>
                                            <th class="px-3 px-md-4">Timetable Name</th>
                                            <th class="px-3 px-md-4">Class</th>
                                            <th class="px-3 px-md-4">Type</th>
                                            <th class="px-3 px-md-4">Year</th>
                                            <th class="px-3 px-md-4 text-center" width="120">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tt_records->where('my_class_id', $mc->id) as $ttr)
                                            <tr>
                                                <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                                <td class="px-3 px-md-4">
                                                    <span class="fw-semibold">{{ $ttr->name }}</span>
                                                    @if($ttr->description)
                                                    <small class="text-muted d-block">{{ \Illuminate\Support\Str::limit($ttr->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td class="px-3 px-md-4">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        <i class="icon-users me-1"></i>
                                                        {{ $ttr->my_class->name }}
                                                    </span>
                                                </td>
                                                <td class="px-3 px-md-4">
                                                    @if($ttr->exam_id)
                                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                                            <i class="icon-pencil me-1"></i>
                                                            {{ $ttr->exam->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success bg-opacity-10 text-success">
                                                            <i class="icon-calendar me-1"></i>
                                                            Class Timetable
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-3 px-md-4">
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        <i class="icon-calendar me-1"></i>
                                                        {{ $ttr->year }}
                                                    </span>
                                                </td>
                                                <td class="px-3 px-md-4 text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                                type="button" data-toggle="dropdown" 
                                                                aria-haspopup="true" aria-expanded="false">
                                                            <i class="icon-menu9"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                                            <a href="{{ route('ttr.show', $ttr->id) }}" class="dropdown-item py-2">
                                                                <i class="icon-eye text-primary me-2"></i>
                                                                View Timetable
                                                            </a>

                                                            @if(Qs::userIsTeamSA())
                                                            <div class="dropdown-divider"></div>
                                                            <a href="{{ route('ttr.manage', $ttr->id) }}" class="dropdown-item py-2">
                                                                <i class="icon-plus-circle2 text-success me-2"></i>
                                                                Manage Slots
                                                            </a>
                                                            <a href="{{ route('ttr.edit', $ttr->id) }}" class="dropdown-item py-2">
                                                                <i class="icon-pencil text-info me-2"></i>
                                                                Edit Details
                                                            </a>
                                                            @endif

                                                            @if(Qs::userIsSuperAdmin())
                                                            <div class="dropdown-divider"></div>
                                                            <a id="{{ $ttr->id }}" onclick="confirmDelete(this.id)" 
                                                               href="#" class="dropdown-item text-danger py-2">
                                                                <i class="icon-trash me-2"></i>
                                                                Delete Timetable
                                                            </a>
                                                            <form method="post" id="item-delete-{{ $ttr->id }}" 
                                                                  action="{{ route('ttr.destroy', $ttr->id) }}" 
                                                                  class="hidden">@csrf @method('delete')</form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="icon-calendar icon-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No timetables found for {{ $mc->name }}</h6>
                                <p class="small text-muted">Click "Create Timetable" to add one.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Success/Error Toast Notification Area -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="icon-checkmark-circle me-2"></i>
                <span id="toastMessage">Timetable created successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<style>
/* Existing styles remain the same, adding a few more */

/* Toast Container */
.toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
}

.toast {
    min-width: 250px;
    border-radius: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.toast.show {
    opacity: 1;
}

/* Loading Animation for Table */
.table tbody tr {
    transition: all 0.2s ease;
}

/* Hover effect for action buttons */
.dropdown-item i {
    transition: transform 0.2s ease;
}

.dropdown-item:hover i {
    transform: scale(1.1);
}

/* Responsive improvements */
@media (max-width: 767.98px) {
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

/* Print styles */
@media print {
    .card-header .d-flex,
    .dropdown,
    .btn,
    .nav-tabs,
    .toast-container {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .table {
        width: 100% !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for better dropdown experience
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select-search').select2({
            width: '100%',
            allowClear: true,
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option';
            },
            dropdownParent: $('#add-tt')
        });
    }
    
    // Handle form submission loading state
    const createForm = document.getElementById('createTimetableForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('ajax-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
            }
        });
    }
    
    // Handle reset button
    const resetBtn = document.getElementById('resetFormBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Reset form fields
            document.getElementById('createTimetableForm').reset();
            // Reset Select2 fields
            setTimeout(() => {
                $('.select-search').val('').trigger('change');
                const submitBtn = document.getElementById('ajax-btn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="icon-paperplane me-2"></i>Create Timetable';
                }
                // Show reset confirmation
                showToast('Form has been reset', 'info');
            }, 100);
        });
    }
    
    // Handle responsive table issues
    function adjustTableResponsive() {
        if (window.innerWidth < 768) {
            $('.table-responsive').each(function() {
                if (!$(this).find('table').length) {
                    $(this).addClass('has-scroll');
                }
            });
        }
    }
    
    adjustTableResponsive();
    
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(adjustTableResponsive, 250);
    });
    
    // Smooth scroll to active tab on page load
    const activeTab = document.querySelector('.nav-link.active[data-toggle="tab"]');
    if (activeTab && window.innerWidth < 768) {
        setTimeout(() => {
            activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }, 100);
    }
    
    // Initialize Bootstrap toasts
    if (typeof bootstrap !== 'undefined') {
        const toastEl = document.getElementById('successToast');
        if (toastEl) {
            window.toast = new bootstrap.Toast(toastEl);
        }
    }
    
    // Check for session success message
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'danger');
    @endif
});

// Show toast notification
function showToast(message, type = 'success') {
    const toastEl = document.getElementById('successToast');
    if (toastEl) {
        const toastBody = toastEl.querySelector('.toast-body span');
        if (toastBody) {
            toastBody.textContent = message;
        }
        
        // Change toast color based on type
        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-info', 'bg-warning');
        if (type === 'success') toastEl.classList.add('bg-success');
        else if (type === 'error') toastEl.classList.add('bg-danger');
        else if (type === 'info') toastEl.classList.add('bg-info');
        else if (type === 'warning') toastEl.classList.add('bg-warning');
        
        // Show toast
        if (typeof bootstrap !== 'undefined' && window.toast) {
            window.toast.show();
        } else {
            toastEl.classList.add('show');
            setTimeout(() => {
                toastEl.classList.remove('show');
            }, 3000);
        }
    }
}

// Enhanced delete confirmation
function confirmDelete(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! All timetable data including all slots will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the timetable.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('item-delete-' + id).submit();
            }
        });
    } else {
        if (confirm('Are you sure? You won\'t be able to revert this!')) {
            document.getElementById('item-delete-' + id).submit();
        }
    }
}
</script>

@endsection