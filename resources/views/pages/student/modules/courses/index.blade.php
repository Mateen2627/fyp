@extends('layouts.master')
@section('page_title', 'Courses')
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
        --secondary: #64748b;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }
    
    /* Main Cards */
    .dashboard-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 28px;
    }
    
    /* Card Header */
    .card-header-modern {
        background: white;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-modern i {
        font-size: 20px;
        color: var(--primary);
    }
    
    .card-header-modern h6 {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .card-header-modern span {
        margin-left: auto;
        font-size: 12px;
        color: var(--gray);
        background: var(--light);
        padding: 4px 12px;
        border-radius: 20px;
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
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: var(--light);
    }
    
    /* Course Badge */
    .course-title {
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Code Badge */
    .code-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-family: monospace;
        color: var(--gray);
    }
    
    .code-badge i {
        font-size: 11px;
        color: #4f46e5;
    }
    
    /* Teacher Badge */
    .teacher-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #4f46e5;
    }
    
    .teacher-badge i {
        font-size: 11px;
    }
    
    /* Notes Badge */
    .notes-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        background: #fefce8;
        color: #854d0e;
        max-width: 250px;
    }
    
    .notes-badge i {
        font-size: 10px;
        color: #eab308;
    }
    
    .notes-badge span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Timetable Table */
    .table-bordered-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-bordered-modern thead th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border: 1px solid var(--border);
    }
    
    .table-bordered-modern tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--dark);
        border: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .table-bordered-modern tbody tr:hover {
        background: var(--light);
    }
    
    /* Day Badge */
    .day-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        color: var(--primary);
    }
    
    .day-badge i {
        font-size: 14px;
    }
    
    /* Time Slot Badge */
    .time-slot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .time-slot i {
        font-size: 11px;
        color: var(--gray);
    }
    
    /* Subject Badge */
    .subject-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        color: #4f46e5;
    }
    
    .subject-badge i {
        font-size: 11px;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .table-bordered-modern thead th,
        .table-bordered-modern tbody td {
            padding: 10px 12px;
        }
        
        .code-badge, .teacher-badge, .notes-badge {
            font-size: 10px;
            padding: 3px 8px;
        }
        
        .time-slot, .subject-badge {
            font-size: 10px;
            padding: 3px 8px;
        }
    }
</style>

<!-- Courses Card -->
<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-book-open"></i>
        <h6 class="mb-0">Courses Assigned to Your Class</h6>
        <span><i class="fas fa-graduation-cap"></i> {{ $courses->count() }} Courses</span>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Code</th>
                    <th>Teacher</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $item)
                    <tr>
                        <td>
                            <span class="course-title">{{ optional($item->course)->title ?: '—' }}</span>
                        </td>
                        <td>
                            @if(optional($item->course)->code)
                                <span class="code-badge">
                                    <i class="fas fa-hashtag"></i>
                                    {{ $item->course->code }}
                                </span>
                            @else
                                <span class="code-badge">—</span>
                            @endif
                        </td>
                        <td>
                            @if(optional($item->teacher)->name)
                                <span class="teacher-badge">
                                    <i class="fas fa-chalkboard-user"></i>
                                    {{ $item->teacher->name }}
                                </span>
                            @else
                                <span class="teacher-badge">
                                    <i class="fas fa-user-slash"></i>
                                    Not assigned
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->notes)
                                <span class="notes-badge">
                                    <i class="fas fa-pen"></i>
                                    <span>{{ \Illuminate\Support\Str::limit($item->notes, 40) }}</span>
                                </span>
                            @else
                                <span class="code-badge">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-book-open"></i>
                                <p>No courses are assigned for your class yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Timetable Card -->
<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-calendar-alt"></i>
        <h6 class="mb-0">Class Timetable {{ $ttr ? '(' . $ttr->name . ')' : '' }}</h6>
        <span><i class="fas fa-clock"></i> {{ $timeTables->count() }} Sessions</span>
    </div>
    
    <div class="table-responsive">
        <table class="table-bordered-modern">
            <thead>
                <tr>
                    <th>Day / Date</th>
                    <th>Time Slot</th>
                    <th>Subject</th>
                </tr>
            </thead>
            <tbody>
                @forelse($timeTables as $tt)
                    <tr>
                        <td>
                            <span class="day-badge">
                                <i class="fas fa-calendar-day"></i>
                                {{ $tt->day ?: $tt->exam_date ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="time-slot">
                                <i class="fas fa-clock"></i>
                                {{ optional($tt->time_slot)->full ?: ($tt->timestamp_from . ' - ' . $tt->timestamp_to) }}
                            </span>
                        </td>
                        <td>
                            @if(optional($tt->subject)->name)
                                <span class="subject-badge">
                                    <i class="fas fa-book"></i>
                                    {{ $tt->subject->name }}
                                </span>
                            @else
                                <span class="code-badge">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <p>No timetable records found for your class.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection