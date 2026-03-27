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
                <a href="#" class="nav-link dropdown-toggle px-3 px-sm-4" data-toggle="dropdown">
                    <i class="icon-calendar2 me-1"></i>
                    <span class="d-none d-sm-inline">Show Timetables</span>
                    <span class="d-sm-none">Timetables</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                    @foreach($my_classes as $mc)
                        <a href="#ttr{{ $mc->id }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                            <i class="icon-users me-2"></i>
                            {{ $mc->name }}
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
                                <form class="ajax-store" method="post" action="{{ route('ttr.store') }}">
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
                                                class="form-control select" name="my_class_id" id="my_class_id">
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
                                        <select class="select form-control" name="exam_id" id="exam_id">
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
                                        <button type="reset" class="btn btn-light me-2">
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
                                Total: {{ $tt_records->where('my_class_id', $mc->id)->count() }} Timetables
                            </span>
                        </div>
                        
                        <div class="card-body p-0 p-md-3">
                            <div class="table-responsive">
                                <table class="table table-hover datatable-button-html5-columns mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 px-md-4">#</th>
                                            <th class="px-3 px-md-4">Timetable Name</th>
                                            <th class="px-3 px-md-4">Class</th>
                                            <th class="px-3 px-md-4">Type</th>
                                            <th class="px-3 px-md-4">Year</th>
                                            <th class="px-3 px-md-4 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tt_records->where('my_class_id', $mc->id) as $ttr)
                                            <tr>
                                                <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                                <td class="px-3 px-md-4">
                                                    <span class="fw-semibold">{{ $ttr->name }}</span>
                                                </td>
                                                <td class="px-3 px-md-4">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
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
                                                        {{ $ttr->year }}
                                                    </span>
                                                </td>
                                                <td class="px-3 px-md-4 text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                                type="button" data-toggle="dropdown">
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* Navigation Tabs Styling */
.nav-tabs {
    border-bottom: 2px solid rgba(0,0,0,0.05);
    gap: 0.25rem;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
}

.nav-tabs::-webkit-scrollbar {
    height: 3px;
}

.nav-tabs::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.2);
    border-radius: 10px;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    transition: all 0.3s ease;
    white-space: nowrap;
    border-radius: 0.5rem 0.5rem 0 0;
}

.nav-tabs .nav-link:hover {
    color: #007bff;
    background: rgba(0,123,255,0.05);
}

.nav-tabs .nav-link.active {
    color: #007bff;
    background: transparent;
    border-bottom: 3px solid #007bff;
}

/* Dropdown Styling */
.dropdown-menu {
    border-radius: 0.75rem;
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

/* Form Styling */
.form-control, .input-group-text {
    border-radius: 0.5rem;
}

.input-group-text {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.1);
}

select.form-control {
    cursor: pointer;
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

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: rgba(0,123,255,0.02);
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

/* Action Button */
.dropdown-toggle.btn-light {
    border: 1px solid #dee2e6;
    background: white;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
}

.dropdown-toggle.btn-light:hover {
    background: #f8f9fa;
    border-color: #c1c9d0;
}

.dropdown-toggle::after {
    display: none;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .table td, .table th {
        padding: 0.75rem !important;
        font-size: 0.875rem;
    }
    
    .badge {
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .btn-group-sm > .btn, .btn-sm {
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .table-responsive {
        border-radius: 0.5rem;
        margin: 0 -1rem;
        width: calc(100% + 2rem);
    }
    
    .table td, .table th {
        white-space: nowrap;
    }
    
    .dropdown-menu {
        min-width: 200px;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tab-pane.active {
    animation: fadeIn 0.5s ease;
}

/* Form Select Styling */
.select2-container--default .select2-selection--single {
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    height: 42px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
}

/* Card Hover Effect */
.card.shadow-sm {
    transition: all 0.3s ease;
}

.card.shadow-sm:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for better dropdown experience
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select').select2({
            width: '100%',
            allowClear: true,
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option';
            }
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
});

// Enhanced delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! All timetable data will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('item-delete-' + id).submit();
        }
    });
}
</script>

@endsection