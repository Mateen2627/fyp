@extends('layouts.master')
@section('page_title', 'My Children')
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
    
    /* Main Card */
    .dashboard-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* Card Header */
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
        font-size: 16px;
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
    
    /* Search Bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }
    
    .filter-bar .form-control-modern {
        max-width: 320px;
        width: 100%;
    }
    
    .form-control-modern {
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .form-control-modern:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    /* Table Container */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table-modern {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    
    .table-modern thead th {
        padding: 14px 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    
    .table-modern tbody td {
        padding: 14px 12px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: #fafbff;
    }
    
    /* Student Photo */
    .student-photo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid var(--border);
    }
    
    /* Student Name */
    .student-name {
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Class Badge */
    .class-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        color: var(--primary);
        white-space: nowrap;
    }
    
    /* Attendance Badge */
    .attendance-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray);
        white-space: nowrap;
    }
    
    .percentage-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #059669;
        white-space: nowrap;
    }
    
    /* Marks Box */
    .marks-box {
        background: var(--light);
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
        min-width: 100px;
    }
    
    .marks-box span {
        color: var(--primary);
        font-weight: 700;
    }
    
    /* Dropdown Menu */
    .dropdown-menu-custom {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        padding: 8px 0;
        min-width: 220px;
        position: absolute;
        z-index: 1000;
        display: none;
    }
    
    .dropdown-menu-custom.show {
        display: block;
    }
    
    .dropdown-item-custom {
        padding: 10px 20px;
        font-size: 13px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--dark);
        text-decoration: none;
        cursor: pointer;
    }
    
    .dropdown-item-custom:hover {
        background: var(--light);
        color: var(--primary);
        padding-left: 26px;
    }
    
    .dropdown-item-custom i {
        width: 18px;
        font-size: 14px;
        color: var(--gray);
    }
    
    .dropdown-item-custom:hover i {
        color: var(--primary);
    }
    
    /* Action Button */
    .action-btn {
        background: transparent;
        border: none;
        color: var(--gray);
        font-size: 18px;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        background: var(--light);
        color: var(--primary);
    }
    
    /* Mobile Card View (for screens below 768px) */
    .mobile-cards-view {
        display: none;
        padding: 16px;
        gap: 16px;
        flex-direction: column;
    }
    
    .child-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.2s;
    }
    
    .child-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .child-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--light);
        border-bottom: 1px solid var(--border);
    }
    
    .child-card-header .student-photo {
        width: 50px;
        height: 50px;
    }
    
    .child-card-header .student-info {
        flex: 1;
    }
    
    .child-card-header .student-name {
        font-size: 16px;
        margin-bottom: 4px;
    }
    
    .child-card-body {
        padding: 16px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .info-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .info-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
    }
    
    .info-value {
        font-size: 13px;
        font-weight: 500;
        color: var(--dark);
    }
    
    .child-card-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .mobile-action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        background: var(--light);
        border-radius: 10px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        color: var(--dark);
        transition: all 0.2s;
    }
    
    .mobile-action-btn i {
        font-size: 14px;
        color: var(--primary);
    }
    
    .mobile-action-btn:hover {
        background: var(--primary);
        color: white;
    }
    
    .mobile-action-btn:hover i {
        color: white;
    }
    
    /* Responsive Breakpoints */
    @media (max-width: 1024px) {
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 8px;
            font-size: 12px;
        }
        
        .student-photo {
            width: 32px;
            height: 32px;
        }
        
        .marks-box {
            min-width: 80px;
            font-size: 11px;
        }
    }
    
    @media (max-width: 768px) {
        .table-view {
            display: none;
        }
        
        .mobile-cards-view {
            display: flex;
        }
        
        .card-header {
            padding: 14px 20px;
        }
        
        .filter-bar {
            padding: 12px 16px;
        }
        
        .filter-bar .form-control-modern {
            max-width: 100%;
        }
    }
    
    @media (max-width: 480px) {
        .child-card-body {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .child-card-footer {
            flex-direction: column;
        }
        
        .mobile-action-btn {
            width: 100%;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header">
        <h6 class="card-title">
            <i class="fas fa-child"></i> My Children
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <!-- Search Filter Bar -->
    <div class="filter-bar">
        <input type="search" class="form-control-modern" id="children-search" placeholder="🔍 Search by name, ADM number, class, or email...">
    </div>

    <!-- Desktop Table View -->
    <div class="table-responsive table-view">
        <table class="table-modern" id="children-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>ADM No</th>
                    <th>Class & Section</th>
                    <th>Attendance</th>
                    <th>Present %</th>
                    <th>Mid/Final Totals</th>
                    <th>Email</th>
                    <th style="width: 80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                    @php
                        $attendanceTotal = (int) ($attendanceCounts[$s->user_id] ?? 0);
                        $presentTotal = (int) ($presentCounts[$s->user_id] ?? 0);
                        $presentPct = $attendanceTotal > 0 ? round(($presentTotal / $attendanceTotal) * 100, 1) : 0;
                        $totals = $markTotals[$s->user_id] ?? null;
                    @endphp
                    <tr data-search="{{ strtolower($s->user->name.' '.$s->adm_no.' '.optional($s->my_class)->name.' '.optional($s->section)->name.' '.$s->user->email) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img class="student-photo" src="{{ Qs::getUserPhoto($s->user) }}" alt="photo" onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                        </td>
                        <td><span class="student-name">{{ $s->user->name }}</span></td>
                        <td><span class="attendance-badge">#{{ $s->adm_no }}</span></td>
                        <td><span class="class-badge"><i class="fas fa-school"></i> {{ $s->my_class->name }} {{ $s->section->name }}</span></td>
                        <td><span class="attendance-badge"><i class="fas fa-calendar-alt"></i> {{ $attendanceTotal }}</span></td>
                        <td><span class="percentage-badge"><i class="fas fa-chart-line"></i> {{ $presentPct }}%</span></td>
                        <td>
                            <div class="marks-box">
                                <i class="fas fa-star"></i> Mid: <span>{{ number_format((float) ($totals->mid_total ?? 0), 2) }}</span><br>
                                <i class="fas fa-star"></i> Final: <span>{{ number_format((float) ($totals->final_total ?? 0), 2) }}</span>
                            </div>
                        </td>
                        <td><span class="attendance-badge"><i class="fas fa-envelope"></i> {{ $s->user->email }}</span></td>
                        <td class="text-center">
                            <div class="dropdown" style="position: relative;">
                                <button class="action-btn dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-custom">
                                    <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="dropdown-item-custom">
                                        <i class="fas fa-user-circle"></i> View Profile
                                    </a>
                                    <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item-custom">
                                        <i class="fas fa-graduation-cap"></i> Marksheet
                                    </a>
                                    <a href="{{ route('parent.attendance.show', $s->id) }}" class="dropdown-item-custom">
                                        <i class="fas fa-calendar-alt"></i> Attendance
                                    </a>
                                    <a href="{{ route('parent.academics.show', $s->id) }}" class="dropdown-item-custom">
                                        <i class="fas fa-book-open"></i> Academics
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards View -->
    <div class="mobile-cards-view" id="mobile-cards-container">
        @foreach($students as $s)
            @php
                $attendanceTotal = (int) ($attendanceCounts[$s->user_id] ?? 0);
                $presentTotal = (int) ($presentCounts[$s->user_id] ?? 0);
                $presentPct = $attendanceTotal > 0 ? round(($presentTotal / $attendanceTotal) * 100, 1) : 0;
                $totals = $markTotals[$s->user_id] ?? null;
            @endphp
            <div class="child-card" data-search="{{ strtolower($s->user->name.' '.$s->adm_no.' '.optional($s->my_class)->name.' '.optional($s->section)->name.' '.$s->user->email) }}">
                <div class="child-card-header">
                    <img class="student-photo" src="{{ Qs::getUserPhoto($s->user) }}" alt="photo" onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                    <div class="student-info">
                        <div class="student-name">{{ $s->user->name }}</div>
                        <div class="class-badge" style="display: inline-flex; margin-top: 4px;">
                            <i class="fas fa-school"></i> {{ $s->my_class->name }} {{ $s->section->name }}
                        </div>
                    </div>
                </div>
                <div class="child-card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-id-card"></i> ADM No</span>
                        <span class="info-value">#{{ $s->adm_no }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar-alt"></i> Attendance</span>
                        <span class="info-value">{{ $attendanceTotal }} days</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-line"></i> Present %</span>
                        <span class="info-value" style="color: #059669; font-weight: 700;">{{ $presentPct }}%</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-envelope"></i> Email</span>
                        <span class="info-value">{{ $s->user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-star"></i> Mid Marks</span>
                        <span class="info-value">{{ number_format((float) ($totals->mid_total ?? 0), 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-star"></i> Final Marks</span>
                        <span class="info-value">{{ number_format((float) ($totals->final_total ?? 0), 2) }}</span>
                    </div>
                </div>
                <div class="child-card-footer">
                    <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="mobile-action-btn">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                    <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" class="mobile-action-btn">
                        <i class="fas fa-graduation-cap"></i> Marksheet
                    </a>
                    <a href="{{ route('parent.attendance.show', $s->id) }}" class="mobile-action-btn">
                        <i class="fas fa-calendar-alt"></i> Attendance
                    </a>
                    <a href="{{ route('parent.academics.show', $s->id) }}" class="mobile-action-btn">
                        <i class="fas fa-book-open"></i> Academics
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('children-search');
        
        // Desktop table search
        const table = document.getElementById('children-table');
        
        // Mobile cards search
        const mobileCards = document.querySelectorAll('.child-card');
        
        function filterContent(searchValue) {
            const value = searchValue.toLowerCase();
            
            // Filter desktop table
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(function (row) {
                    const searchData = row.dataset.search || row.textContent.toLowerCase();
                    row.style.display = !value || searchData.includes(value) ? '' : 'none';
                });
            }
            
            // Filter mobile cards
            mobileCards.forEach(function (card) {
                const searchData = card.dataset.search || card.textContent.toLowerCase();
                card.style.display = !value || searchData.includes(value) ? 'flex' : 'none';
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                filterContent(this.value);
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu-custom.show').forEach(function(menu) {
                    menu.classList.remove('show');
                });
            }
        });
        
        // Toggle dropdown
        document.querySelectorAll('.dropdown-toggle').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const dropdownMenu = this.nextElementSibling;
                dropdownMenu.classList.toggle('show');
            });
        });
    });
</script>

@endsection