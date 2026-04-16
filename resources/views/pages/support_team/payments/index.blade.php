@extends('layouts.master')
@section('page_title', 'Manage Payments')
@section('content')

<!-- Year Selection Card -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="card-title fw-bold mb-0">
            <i class="icon-cash2 text-primary me-2"></i>
            Select Academic Year
        </h5>
    </div>

    <div class="card-body p-4">
        <form method="post" action="{{ route('payments.select_year') }}" id="yearSelectForm">
            @csrf
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="icon-calendar text-muted me-1"></i>
                                    Select Year <span class="text-danger">*</span>
                                </label>
                                <select data-placeholder="Select Year" required id="year" 
                                        name="year" class="form-control select-search">
                                    @foreach($years as $yr)
                                        <option {{ ($selected && $year == $yr->year) ? 'selected' : '' }} 
                                                value="{{ $yr->year }}">
                                            {{ $yr->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="icon-paperplane me-2"></i>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selected)
<!-- Payments Management Card -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h6 class="fw-bold mb-2 mb-md-0">
            <i class="icon-cash2 text-primary me-2"></i>
            Manage Payments for {{ $year }} Session
        </h6>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 me-2">
                Total: {{ $payments->count() }} Payments
            </span>
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-highlight flex-nowrap flex-sm-wrap overflow-auto pb-2 pb-sm-0 mb-4" role="tablist">
            <li class="nav-item me-1">
                <a href="#all-payments" class="nav-link active px-3 px-sm-4" data-toggle="tab" role="tab">
                    <i class="icon-users4 me-1 d-none d-sm-inline"></i>
                    All Classes
                </a>
            </li>
            <li class="nav-item dropdown ms-auto">
                <a href="#" class="nav-link dropdown-toggle px-3 px-sm-4" data-toggle="dropdown">
                    <i class="icon-filter3 me-1"></i>
                    <span class="d-none d-sm-inline">Filter by Class</span>
                    <span class="d-sm-none">Filter</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2" style="max-height: 300px; overflow-y: auto;">
                    @foreach($my_classes as $mc)
                        <a href="#pc-{{ $mc->id }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                            <i class="icon-users me-2"></i>
                            {{ $mc->name }}
                            <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                                {{ $payments->where('my_class_id', $mc->id)->count() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- All Payments Tab -->
            <div class="tab-pane fade show active" id="all-payments" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover datatable-button-html5-columns mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 px-md-4" width="50">#</th>
                                <th class="px-3 px-md-4">Title</th>
                                <th class="px-3 px-md-4 text-end">Amount</th>
                                <th class="px-3 px-md-4">Reference No.</th>
                                <th class="px-3 px-md-4">Class</th>
                                <th class="px-3 px-md-4">Payment Method</th>
                                <th class="px-3 px-md-4">Description</th>
                                <th class="px-3 px-md-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $p)
                            <tr class="payment-row">
                                <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                <td class="px-3 px-md-4">
                                    <span class="fw-semibold">{{ $p->title }}</span>
                                </td>
                                <td class="px-3 px-md-4 text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2">
                                        <i class="icon-cash2 me-1"></i>
                                        {{ number_format($p->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <i class="icon-vcard me-1"></i>
                                        {{ $p->ref_no }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    @if($p->my_class_id)
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="icon-users me-1"></i>
                                            {{ $p->my_class->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">All Classes</span>
                                    @endif
                                </td>
                                <td class="px-3 px-md-4">
                                    @php
                                        $methodColors = [
                                            'cash' => 'success',
                                            'bank' => 'primary',
                                            'cheque' => 'warning',
                                            'online' => 'info'
                                        ];
                                        $color = $methodColors[strtolower($p->method)] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                                        <i class="icon-credit-card me-1"></i>
                                        {{ ucwords($p->method) }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="text-muted small">
                                        {{ Str::limit($p->description, 50) ?: 'No description' }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                type="button" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                            <!-- Edit -->
                                            <a href="{{ route('payments.edit', $p->id) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-pencil text-info me-2"></i>
                                                Edit Payment
                                            </a>
                                            
                                            <div class="dropdown-divider"></div>
                                            
                                            <!-- Delete -->
                                            <a id="{{ $p->id }}" onclick="confirmDelete(this.id)" 
                                               href="#" class="dropdown-item text-danger py-2">
                                                <i class="icon-trash me-2"></i>
                                                Delete Payment
                                            </a>
                                            <form method="post" id="item-delete-{{ $p->id }}" 
                                                  action="{{ route('payments.destroy', $p->id) }}" 
                                                  class="hidden">@csrf @method('delete')</form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if($payments->count() > 0)
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-end fw-semibold">Total:</td>
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($payments->sum('amount'), 2) }}
                                </td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Class-specific Payment Tabs -->
            @foreach($my_classes as $mc)
            <div class="tab-pane fade" id="pc-{{ $mc->id }}" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover datatable-button-html5-columns mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 px-md-4" width="50">#</th>
                                <th class="px-3 px-md-4">Title</th>
                                <th class="px-3 px-md-4 text-end">Amount</th>
                                <th class="px-3 px-md-4">Reference No.</th>
                                <th class="px-3 px-md-4">Method</th>
                                <th class="px-3 px-md-4">Description</th>
                                <th class="px-3 px-md-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments->where('my_class_id', $mc->id) as $p)
                            <tr class="payment-row">
                                <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                <td class="px-3 px-md-4">
                                    <span class="fw-semibold">{{ $p->title }}</span>
                                </td>
                                <td class="px-3 px-md-4 text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2">
                                        {{ number_format($p->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $p->ref_no }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    @php
                                        $methodColors = [
                                            'cash' => 'success',
                                            'bank' => 'primary',
                                            'cheque' => 'warning',
                                            'online' => 'info'
                                        ];
                                        $color = $methodColors[strtolower($p->method)] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                                        {{ ucwords($p->method) }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4">
                                    <span class="text-muted small">
                                        {{ Str::limit($p->description, 50) ?: 'No description' }}
                                    </span>
                                </td>
                                <td class="px-3 px-md-4 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" 
                                                type="button" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                            <a href="{{ route('payments.edit', $p->id) }}" 
                                               class="dropdown-item py-2">
                                                <i class="icon-pencil text-info me-2"></i>
                                                Edit
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a id="{{ $p->id }}" onclick="confirmDelete(this.id)" 
                                               href="#" class="dropdown-item text-danger py-2">
                                                <i class="icon-trash me-2"></i>
                                                Delete
                                            </a>
                                            <form method="post" id="item-delete-{{ $p->id }}" 
                                                  action="{{ route('payments.destroy', $p->id) }}" 
                                                  class="hidden">@csrf @method('delete')</form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if($payments->where('my_class_id', $mc->id)->count() > 0)
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-end fw-semibold">Total:</td>
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($payments->where('my_class_id', $mc->id)->sum('amount'), 2) }}
                                </td>
                                <td colspan="4"></td>
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

{{-- Empty State for No Payments --}}
@if($payments->count() == 0)
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-5 text-center">
        <div class="empty-state">
            <i class="icon-cash2 icon-4x text-muted mb-3"></i>
            <h5 class="fw-semibold mb-2">No Payments Found</h5>
            <p class="text-muted mb-0">There are no payment records for the selected year.</p>
            <a href="{{ route('payments.create') }}" class="btn btn-primary mt-3">
                <i class="icon-plus3 me-2"></i>
                Create New Payment
            </a>
        </div>
    </div>
</div>
@endif

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

.payment-row {
    transition: all 0.2s ease;
}

.payment-row:hover {
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

.bg-success.bg-opacity-10 {
    background: rgba(40, 167, 69, 0.1) !important;
}

.bg-primary.bg-opacity-10 {
    background: rgba(0, 123, 255, 0.1) !important;
}

.bg-warning.bg-opacity-10 {
    background: rgba(255, 193, 7, 0.1) !important;
}

.bg-info.bg-opacity-10 {
    background: rgba(23, 162, 184, 0.1) !important;
}

.text-success {
    color: #28a745 !important;
}

/* Dropdown Styling */
.dropdown-menu {
    border-radius: 0.75rem;
    min-width: 180px;
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
    
    .badge {
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .dropdown-menu {
        min-width: 160px;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
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
    }
    
    .badge i {
        display: none;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
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

.tab-pane.active {
    animation: fadeIn 0.5s ease;
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for year selection
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
    const yearSelectForm = document.getElementById('yearSelectForm');
    if (yearSelectForm) {
        yearSelectForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            }
        });
    }
});

// Enhanced delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Payment Record?',
        text: "This will permanently delete the payment record. This action cannot be undone!",
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
</script>
@endsection