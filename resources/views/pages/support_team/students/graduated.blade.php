@extends('layouts.master')
@section('page_title', 'Graduated Students')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-graduation2 text-primary me-2"></i>
            Graduated Students
        </h5>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 me-2">
                Total: {{ $students->count() }} Graduates
            </span>
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-highlight flex-nowrap flex-sm-wrap overflow-auto pb-2 pb-sm-0 mb-4" role="tablist">
            <li class="nav-item me-1">
                <a href="#all-students" class="nav-link active px-3 px-sm-4" data-toggle="tab" role="tab">
                    <i class="icon-users4 me-1 d-none d-sm-inline"></i>
                    All Graduates
                </a>
            </li>
            <li class="nav-item dropdown ms-auto">
                <a href="#" class="nav-link dropdown-toggle px-3 px-sm-4" data-toggle="dropdown">
                    <i class="icon-filter3 me-1"></i>
                    <span class="d-none d-sm-inline">Filter by Class</span>
                    <span class="d-sm-none">Filter</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2" style="max-height: 300px; overflow-y: auto;">
                    @foreach($my_classes as $c)
                    <a href="#c{{ $c->id }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                        <i class="icon-users me-2"></i>
                        {{ $c->name }}
                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                            {{ $students->where('my_class_id', $c->id)->count() }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- All Students Tab -->
            <div class="tab-pane fade show active" id="all-students" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover datatable-button-html5-columns mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 px-md-4">#</th>
                                <th class="px-3 px-md-4">Student</th>
                                <th class="px-3 px-md-4">ADM No.</th>
                                <th class="px-3 px-md-4">Class & Section</th>
                                <th class="px-3 px-md-4">Graduation Year</th>
                                <th class="px-3 px-md-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $s)
                            <tr class="student-row">
                                <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                <td class="px-3 px-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            <img class="rounded-circle border border-2 border-white shadow-sm" 
                                                 style="height: 45px; width: 45px; object-fit: cover;" 
                                                 src="{{ $s->user->photo }}" 
                                                 alt="{{ $s->user->name }}"
                                                 onerror="this.src='{{ asset('global_assets/images/placeholder.jpg') }}'">
                                        </div>
                                        <div>
                                            <span class="fw-semibold d-block">{{ $s->user->name }}</span>
                                            <small class="text-muted">ID: {{ $s->adm_no }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $s->adm_no }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <div>
                                        <span class="fw-semibold">{{ $s->my_class->name }}</span>
                                        <small class="text-muted d-block">{{ $s->section->name }}</small>
                                    </div>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="icon-calendar me-1"></i>
                                        {{ $s->grad_date }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                type="button" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                            <!-- View Profile -->
                                            <a href="{{ route('students.show', Qs::hash($s->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-eye text-primary me-2"></i>
                                                View Profile
                                            </a>

                                            @if(Qs::userIsTeamSA())
                                            <div class="dropdown-divider"></div>
                                            
                                            <!-- Edit -->
                                            <a href="{{ route('students.edit', Qs::hash($s->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-pencil text-info me-2"></i>
                                                Edit Details
                                            </a>
                                            
                                            <!-- Reset Password -->
                                            <a href="{{ route('st.reset_pass', Qs::hash($s->user->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-lock text-warning me-2"></i>
                                                Reset Password
                                            </a>
                                            
                                            <!-- Not Graduated -->
                                            <a id="{{ Qs::hash($s->id) }}" href="#" 
                                               onclick="event.preventDefault(); notGraduated(this.id)" 
                                               class="dropdown-item py-2">
                                                <i class="icon-stairs-down text-success me-2"></i>
                                                Mark as Not Graduated
                                            </a>
                                            <form method="post" id="ng-{{ Qs::hash($s->id) }}" 
                                                  action="{{ route('st.not_graduated', Qs::hash($s->id)) }}" 
                                                  class="hidden">@csrf @method('put')</form>
                                            @endif

                                            <div class="dropdown-divider"></div>
                                            
                                            <!-- Marksheet -->
                                            <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-check text-success me-2"></i>
                                                View Marksheet
                                            </a>

                                            @if(Qs::userIsSuperAdmin())
                                            <div class="dropdown-divider"></div>
                                            
                                            <!-- Delete -->
                                            <a id="del-{{ Qs::hash($s->user->id) }}" 
                                               onclick="confirmDelete('{{ Qs::hash($s->user->id) }}')" 
                                               href="#" class="dropdown-item text-danger py-2">
                                                <i class="icon-trash me-2"></i>
                                                Delete Record
                                            </a>
                                            <form method="post" id="item-delete-{{ Qs::hash($s->user->id) }}" 
                                                  action="{{ route('students.destroy', Qs::hash($s->user->id)) }}" 
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

            <!-- Class-specific Tabs -->
            @foreach($my_classes as $mc)
            <div class="tab-pane fade" id="c{{$mc->id}}" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover datatable-button-html5-columns mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 px-md-4">#</th>
                                <th class="px-3 px-md-4">Student</th>
                                <th class="px-3 px-md-4">ADM No.</th>
                                <th class="px-3 px-md-4">Section</th>
                                <th class="px-3 px-md-4">Graduation Year</th>
                                <th class="px-3 px-md-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students->where('my_class_id', $mc->id) as $s)
                            <tr class="student-row">
                                <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                <td class="px-3 px-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            <img class="rounded-circle border border-2 border-white shadow-sm" 
                                                 style="height: 40px; width: 40px; object-fit: cover;" 
                                                 src="{{ $s->user->photo }}" 
                                                 alt="{{ $s->user->name }}">
                                        </div>
                                        <span class="fw-semibold">{{ $s->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $s->adm_no }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">{{ $s->section->name }}</td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ $s->grad_date }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                type="button" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                            <!-- Same action buttons as above -->
                                            <a href="{{ route('students.show', Qs::hash($s->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-eye text-primary me-2"></i>
                                                View Profile
                                            </a>
                                            
                                            @if(Qs::userIsTeamSA())
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('students.edit', Qs::hash($s->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-pencil text-info me-2"></i>
                                                Edit
                                            </a>
                                            <a id="{{ Qs::hash($s->id) }}" href="#" 
                                               onclick="event.preventDefault(); notGraduated(this.id)" 
                                               class="dropdown-item py-2">
                                                <i class="icon-stairs-down text-success me-2"></i>
                                                Not Graduated
                                            </a>
                                            <form method="post" id="ng-{{ Qs::hash($s->id) }}" 
                                                  action="{{ route('st.not_graduated', Qs::hash($s->id)) }}" 
                                                  class="hidden">@csrf @method('put')</form>
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

/* Dropdown Styling */
.dropdown-menu {
    border-radius: 0.75rem;
    min-width: 200px;
    animation: fadeInDown 0.2s ease;
}

.dropdown-item {
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
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
    
    .student-avatar img {
        height: 35px !important;
        width: 35px !important;
    }
    
    .badge {
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .dropdown-menu {
        min-width: 180px;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .table-responsive {
        margin: 0 -1rem;
        width: calc(100% + 2rem);
        border-radius: 0;
    }
    
    .table td, .table th {
        white-space: nowrap;
    }
    
    .student-avatar {
        margin-right: 0.5rem !important;
    }
    
    .student-avatar img {
        height: 30px !important;
        width: 30px !important;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
}

/* Animation */
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

.tab-pane.active {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Student Row Hover Effect */
.student-row {
    position: relative;
}

.student-row::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,123,255,0.1), transparent);
}

/* Stats Badge */
.badge.bg-primary.bg-opacity-10 {
    background: rgba(0,123,255,0.1) !important;
    color: #0056b3 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
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

// Enhanced delete confirmation with SweetAlert
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Student Record?',
        text: "This will permanently delete the student's record. This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete permanently',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('item-delete-' + id).submit();
        }
    });
}

// Not graduated confirmation
function notGraduated(id) {
    Swal.fire({
        title: 'Mark as Not Graduated?',
        text: "This student will be moved back to active students list.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, mark as not graduated',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('ng-' + id).submit();
        }
    });
}
</script>

@endsection