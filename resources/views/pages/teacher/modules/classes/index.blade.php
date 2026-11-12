@extends('layouts.master')
@section('page_title', 'My Classes & Timetable')
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
    
    /* Main Cards */
    .dashboard-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }
    
    /* Card Header */
    .card-header-modern {
        background: white;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-modern i {
        font-size: 18px;
        color: var(--primary);
    }
    
    .card-header-modern h6 {
        font-size: 15px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }
    
    /* Card Body */
    .card-body-modern {
        padding: 24px;
    }
    
    /* Form Elements */
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
    
    /* Button */
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
        gap: 8px;
        cursor: pointer;
        width: 100%;
        justify-content: center;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    /* Table */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern thead th {
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border-bottom: 1px solid var(--border);
    }
    
    .table-modern tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: var(--light);
    }
    
    /* Day Badge */
    .day-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }
    
    .day-badge i {
        font-size: 13px;
        color: var(--primary);
    }
    
    /* Time Slot Badge */
    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--light);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: var(--gray);
    }
    
    .time-badge i {
        font-size: 10px;
        color: var(--primary);
    }
    
    /* Subject Badge */
    .subject-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        color: var(--primary);
    }
    
    .subject-badge i {
        font-size: 10px;
    }

    .teacher-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        color: #047857;
    }

    .teacher-badge i {
        font-size: 10px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-modern {
            padding: 14px 20px;
        }
        
        .card-body-modern {
            padding: 16px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .btn-primary-modern {
            margin-top: 8px;
        }
    }
</style>

<div>
    <!-- Select Timetable Record Card -->
    <div class="dashboard-card">
        <div class="card-header-modern">
            <i class="fas fa-calendar-alt"></i>
            <h6 class="mb-0">Select Class Timetable Record</h6>
        </div>
        <div class="card-body-modern">
            <form method="get">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-list"></i> Timetable Record
                            </label>
                            <select name="ttr_id" class="form-control-modern">
                                <option value="">— Select a timetable record —</option>
                                @foreach($records as $record)
                                    <option value="{{ $record->id }}" {{ (string)$selected === (string)$record->id ? 'selected' : '' }}>
                                        {{ optional($record->my_class)->name }} - {{ $record->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn-primary-modern w-100">
                            <i class="fas fa-eye"></i> View Timetable
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Timetable Display Card -->
    <div class="dashboard-card">
        <div class="card-header-modern">
            <i class="fas fa-table"></i>
            <h6 class="mb-0">Timetable Schedule</h6>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar-day me-1"></i> Day / Date</th>
                        <th><i class="fas fa-clock me-1"></i> Time Slot</th>
                        <th><i class="fas fa-book me-1"></i> Subject</th>
                        <th><i class="fas fa-chalkboard-teacher me-1"></i> Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timeTables as $tt)
                        <tr>
                            <td>
                                <span class="day-badge">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $tt->day ?: $tt->exam_date ?: '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="time-badge">
                                    <i class="fas fa-clock"></i>
                                    {{ optional($tt->time_slot)->full ?: '—' }}
                                </span>
                            </td>
                            <td>
                                @if(optional($tt->subject)->name)
                                    <span class="subject-badge">
                                        <i class="fas fa-book-open"></i>
                                        {{ $tt->subject->name }}
                                    </span>
                                @else
                                    <span class="time-badge">—</span>
                                @endif
                            </td>
                            <td>
                                @if($tt->assigned_teachers->isNotEmpty())
                                    <span class="teacher-badge">
                                        <i class="fas fa-user-tie"></i>
                                        {{ $tt->assigned_teachers->pluck('name')->implode(', ') }}
                                    </span>
                                @else
                                    <span class="text-muted">No teacher assigned</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>No timetable data available.</p>
                                    <small class="text-muted">Please select a timetable record to view.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
