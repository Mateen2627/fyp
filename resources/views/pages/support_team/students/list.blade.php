@extends('layouts.master')
@section('page_title', 'Student Information - '.$my_class->name)
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-users text-primary me-2"></i>
            Students List - {{ $my_class->name }}
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-highlight flex-nowrap flex-sm-wrap overflow-auto pb-2 pb-sm-0 mb-4" role="tablist">
            <li class="nav-item me-1">
                <a href="#all-students" class="nav-link active px-3 px-sm-4" data-toggle="tab" role="tab">
                    <i class="icon-users4 me-1 d-none d-sm-inline"></i>
                    All {{ $my_class->name }} Students
                </a>
            </li>
            <li class="nav-item dropdown ms-auto">
                <a href="#" class="nav-link dropdown-toggle px-3 px-sm-4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-grid me-1"></i>
                    <span class="d-none d-sm-inline">Filter by Section</span>
                    <span class="d-sm-none">Sections</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                    @foreach($sections as $s)
                        <a href="#s{{ $s->id }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                            <i class="icon-users me-2"></i>
                            {{ $my_class->name.' '.$s->name }}
                            <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                                {{ $students->where('section_id', $s->id)->count() }}
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
                                <th class="px-3 px-md-4" width="50">#</th>
                                <th class="px-3 px-md-4">Student</th>
                                <th class="px-3 px-md-4">Admission No.</th>
                                <th class="px-3 px-md-4">Section</th>
                                <th class="px-3 px-md-4">Email</th>
                                <th class="px-3 px-md-4 text-center" width="100">Actions</th>
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
                                                 src="{{ Qs::getUserPhoto($s->user) }}"
                                                 alt="{{ $s->user->name }}"
                                                 loading="lazy"
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
                                        <i class="icon-vcard me-1"></i>
                                        {{ $s->adm_no }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="icon-grid me-1"></i>
                                        {{ $my_class->name.' '.$s->section->name }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="small">{{ $s->user->email ?: 'N/A' }}</span>
                                </td>
                                <td class="px-3 px-md-4 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                type="button" data-toggle="dropdown" 
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-menu9"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
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
                                                Edit Details
                                            </a>
                                            <a href="{{ route('st.reset_pass', Qs::hash($s->user->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-lock text-warning me-2"></i>
                                                Reset Password
                                            </a>
                                            @endif
                                            
                                            <div class="dropdown-divider"></div>
                                            <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-check text-success me-2"></i>
                                                View Marksheet
                                            </a>

                                            @if(Qs::userIsSuperAdmin())
                                            <div class="dropdown-divider"></div>
                                            <a id="{{ Qs::hash($s->user->id) }}" 
                                               onclick="confirmDelete(this.id)" 
                                               href="#" class="dropdown-item text-danger py-2">
                                                <i class="icon-trash me-2"></i>
                                                Delete Student
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
                        @if($students->count() > 0)
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="6" class="text-end fw-semibold">
                                    Total Students: {{ $students->count() }}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Section-specific Tabs -->
            @foreach($sections as $se)
                <div class="tab-pane fade" id="s{{$se->id}}" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover datatable-button-html5-columns mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 px-md-4" width="50">#</th>
                                    <th class="px-3 px-md-4">Student</th>
                                    <th class="px-3 px-md-4">Admission No.</th>
                                    <th class="px-3 px-md-4">Email</th>
                                    <th class="px-3 px-md-4 text-center" width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students->where('section_id', $se->id) as $sr)
                                <tr class="student-row">
                                    <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                    <td class="px-3 px-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar me-3">
                                                <img class="rounded-circle border border-2 border-white shadow-sm" 
                                                     style="height: 40px; width: 40px; object-fit: cover;" 
                                                     src="{{ Qs::getUserPhoto($sr->user) }}"
                                                     alt="{{ $sr->user->name }}"
                                                     loading="lazy"
                                                     onerror="this.src='{{ asset('global_assets/images/placeholder.jpg') }}'">
                                            </div>
                                            <div>
                                                <span class="fw-semibold d-block">{{ $sr->user->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 px-md-4">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <i class="icon-vcard me-1"></i>
                                            {{ $sr->adm_no }}
                                        </span>
                                    </td>
                                    <td class="px-3 px-md-4">
                                        <span class="small">{{ $sr->user->email ?: 'N/A' }}</span>
                                    </td>
                                    <td class="px-3 px-md-4 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" 
                                                    type="button" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                                <a href="{{ route('students.show', Qs::hash($sr->id)) }}" 
                                                   class="dropdown-item py-2">
                                                    <i class="icon-eye text-primary me-2"></i>
                                                    View Info
                                                </a>
                                                
                                                @if(Qs::userIsTeamSA())
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('students.edit', Qs::hash($sr->id)) }}" 
                                                   class="dropdown-item py-2">
                                                    <i class="icon-pencil text-info me-2"></i>
                                                    Edit
                                                </a>
                                                <a href="{{ route('st.reset_pass', Qs::hash($sr->user->id)) }}" 
                                                   class="dropdown-item py-2">
                                                    <i class="icon-lock text-warning me-2"></i>
                                                    Reset Password
                                                </a>
                                                @endif
                                                
                                                <div class="dropdown-divider"></div>
                                                <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($sr->user->id)) }}" 
                                                   class="dropdown-item py-2">
                                                    <i class="icon-check text-success me-2"></i>
                                                    Marksheet
                                                </a>

                                                @if(Qs::userIsSuperAdmin())
                                                <div class="dropdown-divider"></div>
                                                <a id="{{ Qs::hash($sr->user->id) }}" 
                                                   onclick="confirmDelete(this.id)" 
                                                   href="#" class="dropdown-item text-danger py-2">
                                                    <i class="icon-trash me-2"></i>
                                                    Delete
                                                </a>
                                                <form method="post" id="item-delete-{{ Qs::hash($sr->user->id) }}" 
                                                      action="{{ route('students.destroy', Qs::hash($sr->user->id)) }}" 
                                                      class="hidden">@csrf @method('delete')</form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if($students->where('section_id', $se->id)->count() > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="5" class="text-end fw-semibold">
                                        Total: {{ $students->where('section_id', $se->id)->count() }} Students
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Empty State for No Students -->
@if($students->count() == 0)
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-5 text-center">
        <div class="empty-state">
            <i class="icon-users icon-4x text-muted mb-3"></i>
            <h5 class="fw-semibold mb-2">No Students Found</h5>
            <p class="text-muted mb-0">There are no students in {{ $my_class->name }} class.</p>
        </div>
    </div>
</div>
@endif

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

.student-row {
    transition: all 0.2s ease;
}

.student-row:hover {
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
    min-width: 180px;
    animation: fadeInDown 0.2s ease;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

.dropdown-item {
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

.dropdown-item i {
    width: 1.25rem;
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

/* Table Footer */
tfoot {
    font-weight: 500;
    border-top: 2px solid #dee2e6;
}

tfoot td {
    padding: 0.75rem;
    font-size: 0.875rem;
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
    
    .badge i {
        display: none;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .student-avatar img {
        height: 35px !important;
        width: 35px !important;
    }
    
    .student-avatar {
        margin-right: 0.75rem !important;
    }
    
    .dropdown-menu {
        min-width: 170px;
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
        border-radius: 0;
    }
    
    .table td, .table th {
        white-space: nowrap;
    }
    
    .student-avatar img {
        height: 30px !important;
        width: 30px !important;
    }
    
    .student-avatar {
        margin-right: 0.5rem !important;
    }
    
    .empty-state {
        padding: 1rem;
    }
    
    .empty-state i {
        font-size: 3rem;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
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

.card, .tab-pane.active {
    animation: fadeIn 0.5s ease;
}

/* Print Styles */
@media print {
    .navbar, .card-header button, .dropdown, .btn-light {
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

/* Focus States */
.dropdown-toggle:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Smooth scroll to active tab on page load (mobile)
    const activeTab = document.querySelector('.nav-link.active[data-toggle="tab"]');
    if (activeTab && window.innerWidth < 768) {
        setTimeout(() => {
            activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }, 100);
    }
    
    // Handle responsive table issues
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Adjust any responsive elements here
        }, 250);
    });
});

// Enhanced delete confirmation
function confirmDelete(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Student?',
            text: "This action cannot be undone. The student's record will be permanently deleted from the system.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete student',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('item-delete-' + id).submit();
            }
        });
    } else {
        if (confirm('Delete this student? This action cannot be undone!')) {
            document.getElementById('item-delete-' + id).submit();
        }
    }
}
</script>

@endsection