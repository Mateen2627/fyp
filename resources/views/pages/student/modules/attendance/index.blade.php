@extends('layouts.master')
@section('page_title', 'Attendance History')
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
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 6px;
    }
    
    .stat-number.total { color: var(--dark); }
    .stat-number.present { color: var(--success); }
    .stat-number.late { color: var(--warning); }
    .stat-number.percentage { color: var(--primary); }
    
    .stat-label {
        font-size: 13px;
        color: var(--gray);
        font-weight: 500;
    }
    
    /* Filter Card */
    .filter-card, .records-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .card-header {
        background: white;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-title i {
        font-size: 18px;
        color: var(--primary);
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Form Elements */
    .form-label-modern {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }
    
    .form-label-modern i {
        margin-right: 6px;
        color: var(--primary);
    }
    
    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
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
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        width: 100%;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    /* Filter Bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }
    
    .filter-bar .form-control-modern {
        max-width: 280px;
    }
    
    /* Table */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern thead th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border-bottom: 1px solid var(--border);
    }
    
    .table-modern tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: #fafbff;
    }
    
    /* Status Badges */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success {
        background: #ecfdf5;
        color: #059669;
    }
    
    .badge-danger {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .badge-warning {
        background: #fffbeb;
        color: #d97706;
    }
    
    .badge-info {
        background: #e0f2fe;
        color: #0284c7;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    .empty-state p {
        font-size: 14px;
        color: #94a3b8;
        margin-bottom: 0;
    }
    
    /* Footer */
    .card-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        background: white;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }
    
    @media (max-width: 768px) {
        .card-header {
            padding: 14px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .filter-bar {
            flex-direction: column;
        }
        
        .filter-bar .form-control-modern {
            max-width: 100%;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
    }
</style>

<div>
    <!-- Filter Card -->
    <div class="filter-card">
        <div class="card-header">
            <h6 class="card-title"><i class="fas fa-calendar-alt"></i> My Attendance History</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('student.attendance.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-modern"><i class="fas fa-chart-line"></i> Filter</label>
                    <select name="period" class="form-control-modern">
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                        <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>This Week</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern"><i class="fas fa-calendar-day"></i> From</label>
                    <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-control-modern">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern"><i class="fas fa-calendar-day"></i> To</label>
                    <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="form-control-modern">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-primary-modern">
                        <i class="fas fa-search"></i> Apply Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number total">{{ $summary['total'] }}</div>
            <div class="stat-label"><i class="fas fa-calendar-alt me-1"></i> Total Records</div>
        </div>
        <div class="stat-card">
            <div class="stat-number present">{{ $summary['present'] }}</div>
            <div class="stat-label"><i class="fas fa-check-circle me-1"></i> Present</div>
        </div>
        <div class="stat-card">
            <div class="stat-number late">{{ $summary['late'] }}</div>
            <div class="stat-label"><i class="fas fa-clock me-1"></i> Late</div>
        </div>
        <div class="stat-card">
            <div class="stat-number percentage">{{ $summary['percentage'] }}%</div>
            <div class="stat-label"><i class="fas fa-chart-line me-1"></i> Attendance Percentage</div>
        </div>
    </div>

    <!-- Records Card -->
    <div class="records-card">
        <div class="card-header">
            <h6 class="card-title"><i class="fas fa-table"></i> Records from {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</h6>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <input type="search" class="form-control-modern table-search" data-table-target="student-attendance-table" placeholder="Search date, teacher, status, or remarks...">
            <select class="form-control-modern table-status-filter" data-table-target="student-attendance-table">
                <option value="">All Statuses</option>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
                <option value="excused">Excused</option>
            </select>
        </div>
        
        <div class="table-responsive">
            <table class="table-modern" id="student-attendance-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-day me-1"></i> Date</th>
                        <th><i class="fas fa-flag-checkered me-1"></i> Status</th>
                        <th><i class="fas fa-user-check me-1"></i> Marked By</th>
                        <th><i class="fas fa-comment me-1"></i> Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $row)
                        <tr data-status="{{ $row->status }}" data-search="{{ strtolower(optional($row->attendance_date)->format('Y-m-d').' '.$row->status.' '.optional($row->markedBy)->name.' '.$row->remarks) }}">
                            <td>
                                <strong>{{ optional($row->attendance_date)->format('Y-m-d') }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($row->status) {
                                        'present' => 'badge-success',
                                        'absent' => 'badge-danger',
                                        'late' => 'badge-warning',
                                        default => 'badge-info'
                                    };
                                    $statusIcon = match($row->status) {
                                        'present' => 'fa-check-circle',
                                        'absent' => 'fa-times-circle',
                                        'late' => 'fa-clock',
                                        default => 'fa-calendar'
                                    };
                                @endphp
                                <span class="badge-modern {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td>{{ optional($row->markedBy)->name ?: '-' }}</td>
                            <td>{{ $row->remarks ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>No attendance records found for this period.</p>
                                    <small class="text-muted">Try adjusting your date filter.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($records->hasPages())
            <div class="card-footer">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function filterTable() {
            const table = document.getElementById('student-attendance-table');
            if (!table) return;

            const search = (document.querySelector('.table-search[data-table-target="student-attendance-table"]')?.value || '').toLowerCase();
            const status = document.querySelector('.table-status-filter[data-table-target="student-attendance-table"]')?.value || '';

            table.querySelectorAll('tbody tr').forEach(function (row) {
                if (!row.dataset.search && !row.dataset.status) return;

                const matchesSearch = !search || (row.dataset.search || row.textContent.toLowerCase()).includes(search);
                const matchesStatus = !status || row.dataset.status === status;
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        document.querySelectorAll('[data-table-target="student-attendance-table"]').forEach(function (control) {
            control.addEventListener('input', filterTable);
            control.addEventListener('change', filterTable);
        });
    });
</script>

@endsection