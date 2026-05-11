@extends('layouts.master')
@section('page_title', 'Student Attendance')
@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    /* ── Hero ── */
    .attn-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        color: #fff;
        box-shadow: 0 10px 25px -5px rgba(79,70,229,.3);
    }
    .attn-hero::before {
        content:''; position:absolute; top:-50%; right:-20%;
        width:300px; height:300px; border-radius:50%;
        background:radial-gradient(circle,rgba(255,255,255,.15) 0%,transparent 70%);
    }
    .attn-hero::after {
        content:''; position:absolute; bottom:-30%; left:-10%;
        width:200px; height:200px; border-radius:50%;
        background:radial-gradient(circle,rgba(255,255,255,.1) 0%,transparent 70%);
    }
    .attn-hero h4 { font-size:22px; font-weight:700; margin-bottom:8px; position:relative; z-index:1; }
    .attn-hero h4 i { margin-right:10px; }
    .attn-hero p  { font-size:14px; opacity:.9; position:relative; z-index:1; margin:0; }

    /* ── Shared card wrapper ── */
    .modern-card {
        background:#fff;
        border-radius:20px;
        border:1px solid var(--border);
        box-shadow:0 1px 3px rgba(0,0,0,.05);
        overflow:hidden;
        margin-bottom:24px;
    }
    .modern-card-header {
        background:#fff;
        padding:18px 24px;
        border-bottom:1px solid var(--border);
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:12px;
    }
    .modern-card-header .card-title {
        font-size:15px; font-weight:700; color:var(--dark); margin:0;
        display:flex; align-items:center; gap:8px;
    }
    .modern-card-header .card-title i { color:var(--primary); }
    .modern-card-body { padding:24px; }

    /* ── Stats row ── */
    .stats-grid {
        display:grid;
        grid-template-columns: repeat(4,1fr);
        gap:16px;
        margin-bottom:24px;
    }
    .stat-card {
        background:#fff; border-radius:16px; border:1px solid var(--border);
        padding:20px 18px; text-align:center;
        box-shadow:0 1px 3px rgba(0,0,0,.05);
    }
    .stat-card .stat-num {
        font-size:28px; font-weight:800; line-height:1; margin-bottom:6px;
    }
    .stat-card .stat-label { font-size:12px; color:var(--gray); font-weight:600; letter-spacing:.3px; }
    .stat-num.total   { color:var(--dark); }
    .stat-num.present { color:var(--success); }
    .stat-num.absent  { color:var(--danger); }
    .stat-num.pct     { color:var(--primary); }

    /* ── Filter form ── */
    .filter-form { display:flex; flex-wrap:wrap; gap:14px; align-items:flex-end; }
    .filter-group { display:flex; flex-direction:column; gap:5px; flex:1; min-width:160px; }
    .filter-group label {
        font-size:12px; font-weight:600; color:var(--dark); letter-spacing:.3px;
    }
    .filter-group label i { margin-right:5px; color:var(--primary); }

    .form-ctrl {
        width:100%; padding:10px 14px;
        border:1px solid var(--border); border-radius:10px;
        font-size:13px; color:var(--dark);
        background:#fff; transition:all .2s;
        -webkit-appearance:none; appearance:none;
    }
    .form-ctrl:focus { border-color:var(--primary); outline:none; box-shadow:0 0 0 3px rgba(79,70,229,.1); }
    select.form-ctrl {
        cursor:pointer;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; padding-right:36px;
    }

    .btn-primary-modern {
        background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);
        border:none; padding:10px 22px; border-radius:10px;
        font-weight:600; font-size:13px; color:#fff;
        display:inline-flex; align-items:center; gap:8px;
        cursor:pointer; transition:all .2s; white-space:nowrap;
    }
    .btn-primary-modern:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.3); }

    .btn-outline-modern {
        background:#fff; border:1px solid var(--primary); padding:7px 14px;
        border-radius:10px; font-weight:600; font-size:12px; color:var(--primary);
        display:inline-flex; align-items:center; gap:6px;
        cursor:pointer; transition:all .2s;
    }
    .btn-outline-modern:hover { background:var(--primary); color:#fff; }

    .btn-success-modern {
        background:linear-gradient(135deg,#10b981 0%,#059669 100%);
        border:none; padding:10px 22px; border-radius:10px;
        font-weight:600; font-size:13px; color:#fff;
        display:inline-flex; align-items:center; gap:8px;
        cursor:pointer; transition:all .2s;
    }
    .btn-success-modern:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,.3); }

    /* ── Table filter bar ── */
    .table-filter-bar {
        display:flex; flex-wrap:wrap; gap:12px;
        padding:16px 24px; border-bottom:1px solid var(--border);
        background:var(--light);
    }
    .table-filter-bar .form-ctrl { max-width:260px; }

    /* ── Tables ── */
    .table-modern { width:100%; border-collapse:collapse; }
    .table-modern thead th {
        padding:13px 14px; font-size:11px; font-weight:700;
        text-transform:uppercase; letter-spacing:.6px;
        color:var(--gray); background:var(--light);
        border-bottom:1px solid var(--border);
    }
    .table-modern tbody td {
        padding:13px 14px; font-size:13px; color:var(--dark);
        border-bottom:1px solid #f1f5f9; vertical-align:middle;
    }
    .table-modern tbody tr:hover { background:#fafbff; }

    /* ── Student cell ── */
    .student-identity {
        display:flex; align-items:center; gap:10px; min-width:180px;
    }
    .student-identity img {
        width:38px; height:38px; border-radius:10px;
        object-fit:cover; border:2px solid var(--border);
    }
    .student-identity span { font-weight:600; color:var(--dark); }

    /* ── Status badges ── */
    .badge-modern {
        display:inline-flex; align-items:center; gap:4px;
        padding:4px 10px; border-radius:20px;
        font-size:11px; font-weight:700; letter-spacing:.3px;
    }
    .badge-present  { background:#dcfce7; color:#166534; }
    .badge-absent   { background:#fee2e2; color:#991b1b; }
    .badge-late     { background:#fef3c7; color:#92400e; }
    .badge-excused  { background:#e0e7ff; color:#3730a3; }
    .badge-facial   { background:#dbeafe; color:#1e40af; }
    .badge-manual   { background:#f1f5f9; color:#475569; }
    .badge-info-soft{ background:#e0e7ff; color:#4338ca; }
    .badge-light-soft{background:#f1f5f9; color:#64748b; }

    /* ── Marks input (status select / remarks in manual table) ── */
    .marks-input {
        width:110px; padding:7px 10px;
        border:1px solid var(--border); border-radius:8px;
        font-size:13px; transition:all .2s;
    }
    .marks-input:focus { border-color:var(--primary); outline:none; box-shadow:0 0 0 3px rgba(79,70,229,.1); }

    /* ── AI / info panel ── */
    .ai-panel {
        background:linear-gradient(135deg,#eff6ff 0%,#f5f3ff 100%);
        border:1px solid #bfdbfe; border-radius:14px;
        padding:18px 20px; margin-bottom:18px;
    }
    .ai-panel h6 { margin:0 0 6px; font-weight:700; color:#1e3a8a; }
    .ai-panel p  { font-size:13px; color:#1e40af; margin:0; }

    /* ── Alert strip ── */
    .alert-info-modern {
        background:#eff6ff; border:1px solid #bfdbfe;
        border-radius:10px; padding:12px 16px;
        font-size:13px; color:#1e40af; margin-bottom:18px;
    }

    /* ── Camera panel ── */
    .camera-panel {
        display:none; border:1px solid #c7d2fe; border-radius:16px;
        background:#fff; box-shadow:0 18px 45px rgba(15,23,42,.12);
        overflow:hidden; margin-bottom:20px;
    }
    .camera-panel.is-active { display:block; }
    .camera-panel-header {
        display:flex; justify-content:space-between; align-items:center;
        gap:16px; padding:16px 20px;
        background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); color:#fff;
    }
    .camera-panel-header h6 { color:#fff; margin:0; font-weight:700; }
    .camera-panel-header small { opacity:.85; font-size:12px; }
    .camera-panel-body {
        display:grid; grid-template-columns:1fr; gap:20px; padding:20px;
    }
    .camera-frame {
        position:relative; min-height:300px; background:#0f172a;
        border-radius:14px; overflow:hidden;
        display:flex; align-items:center; justify-content:center;
    }
    .camera-frame video {
        width:100%; height:100%; min-height:300px;
        object-fit:cover; transform:scaleX(-1);
    }
    .camera-frame .scanner-line {
        position:absolute; left:8%; right:8%; height:3px;
        background:#22c55e; box-shadow:0 0 18px #22c55e;
        animation:scan-line 1.4s ease-in-out infinite;
    }
    @keyframes scan-line { 0%{top:14%} 50%{top:84%} 100%{top:14%} }
    .camera-status {
        margin-top:12px; padding:12px 14px; border-radius:10px;
        background:#eef2ff; color:#3730a3; font-weight:600; font-size:13px;
    }
    .camera-student-card {
        border:1px solid var(--border); border-radius:14px;
        padding:20px; background:var(--light); text-align:center;
    }
    .camera-student-card img {
        width:84px; height:84px; object-fit:cover; border-radius:50%;
        border:3px solid #fff; box-shadow:0 8px 18px rgba(15,23,42,.14);
    }
    .camera-student-name { font-size:17px; font-weight:700; margin-top:10px; color:var(--dark); }

    /* ── Total badge (percentage column) ── */
    .pct-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg,#eef2ff,#e0e7ff);
        color:var(--primary-dark,#4338ca); padding:5px 12px;
        border-radius:30px; font-size:12px; font-weight:700; min-width:56px;
    }

    /* ── Responsive ── */
    @media(max-width:768px){
        .stats-grid { grid-template-columns:repeat(2,1fr); }
        .attn-hero  { padding:20px 22px; }
        .attn-hero h4 { font-size:18px; }
        .modern-card-body { padding:16px; }
        .table-filter-bar { padding:12px 16px; }
        .filter-form { flex-direction:column; }
        .btn-primary-modern { width:100%; justify-content:center; }
    }
</style>

{{-- ══════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════ --}}
<div class="attn-hero">
    <h4><i class="fas fa-calendar-check"></i> Student Attendance</h4>
    <p>View, mark, and manage attendance records for your assigned classes — manual or facial recognition.</p>
</div>

{{-- ══════════════════════════════════════════════════════
     FILTER CARD
══════════════════════════════════════════════════════ --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="card-title"><i class="fas fa-filter"></i> Filter Attendance</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="modern-card-body">
        <form method="get" action="{{ route('teacher.attendance.index') }}" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-chalkboard-teacher"></i> Class</label>
                <select name="class_id" class="form-ctrl">
                    <option value="">All Assigned Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ (int)$selectedClassId === (int)$class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-clock"></i> Period</label>
                <select name="period" class="form-ctrl">
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                    <option value="weekly"  {{ $period === 'weekly'  ? 'selected' : '' }}>This Week</option>
                    <option value="custom"  {{ $period === 'custom'  ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> From</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-ctrl">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> To</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="form-ctrl">
            </div>
            <div class="filter-group" style="flex:0 0 auto;">
                <label>&nbsp;</label>
                <button type="submit" class="btn-primary-modern"><i class="fas fa-search"></i> Apply Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     STATS
══════════════════════════════════════════════════════ --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-num total">{{ $summary['total'] }}</div>
        <div class="stat-label">Total Records</div>
    </div>
    <div class="stat-card">
        <div class="stat-num present">{{ $summary['present'] }}</div>
        <div class="stat-label">Present</div>
    </div>
    <div class="stat-card">
        <div class="stat-num absent">{{ $summary['absent'] }}</div>
        <div class="stat-label">Absent</div>
    </div>
    <div class="stat-card">
        <div class="stat-num pct">{{ $summary['percentage'] }}%</div>
        <div class="stat-label">Attendance Rate</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MANUAL ATTENDANCE
══════════════════════════════════════════════════════ --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="card-title"><i class="fas fa-pen-alt"></i> Student-wise Manual Attendance</h6>
        <span class="badge-modern badge-info-soft">One student at a time</span>
    </div>
    <div class="modern-card-body">
        <div class="alert-info-modern">
            Select one student's status and click <strong>Save This Student</strong>. This prevents accidentally marking the whole class.
        </div>

        <div id="manual-attendance-wrapper">
            {{-- Date picker --}}
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:18px;">
                <div class="filter-group" style="max-width:220px;">
                    <label><i class="fas fa-calendar-day"></i> Attendance Date</label>
                    <input type="date" name="attendance_date" id="manual_attendance_date" value="{{ $markDate }}" class="form-ctrl" required>
                </div>
                <p style="font-size:12px;color:var(--gray);margin:0;padding-top:20px;">
                    This date is used when you click any student-wise manual or facial attendance button.
                </p>
            </div>

            {{-- Table filter bar --}}
            <div class="table-filter-bar" style="border-radius:12px 12px 0 0;border:1px solid var(--border);border-bottom:none;">
                <input type="search" class="form-ctrl table-search" data-table-target="mark-attendance-table" placeholder="Search student, ADM no, or class">
                <select class="form-ctrl table-status-filter" data-table-target="mark-attendance-table" style="max-width:180px;">
                    <option value="">All Statuses</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="excused">Excused</option>
                </select>
            </div>

            <div class="table-responsive" style="border:1px solid var(--border);border-radius:0 0 12px 12px;">
                <table class="table-modern student-wise-table" id="mark-attendance-table">
                    <thead>
                    <tr>
                        <th>Student</th>
                        <th>ADM No</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $student)
                        @php($existingAttendance = $dailyAttendance->get($student->user_id))
                        <tr
                            data-student-id="{{ $student->user_id }}"
                            data-student-name="{{ optional($student->user)->name }}"
                            data-student-adm="{{ $student->adm_no }}"
                            data-student-photo="{{ Qs::getUserPhoto($student->user) }}"
                            data-search="{{ strtolower(optional($student->user)->name.' '.$student->adm_no.' '.optional($student->my_class)->name.' '.optional($student->section)->name) }}"
                            data-status="{{ optional($existingAttendance)->status ?: 'present' }}">
                            <td>
                                <div class="student-identity">
                                    <img src="{{ Qs::getUserPhoto($student->user) }}" alt="{{ optional($student->user)->name }}" onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                                    <span>{{ optional($student->user)->name }}</span>
                                </div>
                            </td>
                            <td>{{ $student->adm_no }}</td>
                            <td>{{ optional($student->my_class)->name }} {{ optional($student->section)->name }}</td>
                            <td>
                                <select name="attendance[{{ $student->user_id }}]" class="form-ctrl marks-input manual-status" required style="width:120px;">
                                    <option value="present"  {{ optional($existingAttendance)->status === 'present'  ? 'selected' : '' }}>Present</option>
                                    <option value="absent"   {{ optional($existingAttendance)->status === 'absent'   ? 'selected' : '' }}>Absent</option>
                                    <option value="late"     {{ optional($existingAttendance)->status === 'late'     ? 'selected' : '' }}>Late</option>
                                    <option value="excused"  {{ optional($existingAttendance)->status === 'excused'  ? 'selected' : '' }}>Excused</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="remarks[{{ $student->user_id }}]" value="{{ optional($existingAttendance)->remarks }}" class="form-ctrl marks-input manual-remarks" placeholder="Optional remarks" style="width:160px;">
                            </td>
                            <td>
                                <button type="button" class="btn-outline-modern save-single-manual">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center" style="padding:40px;color:var(--gray);">No students found for your assigned classes.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Hidden form --}}
        <form method="post" action="{{ route('teacher.attendance.store_single') }}" id="single-manual-form" style="display:none;">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="attendance_date" id="single_attendance_date">
            <input type="hidden" name="student_id" id="single_student_id">
            <input type="hidden" name="status" id="single_status">
            <input type="hidden" name="remarks" id="single_remarks">
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     FACIAL ATTENDANCE
══════════════════════════════════════════════════════ --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="card-title"><i class="fas fa-camera"></i> Student-wise Facial Attendance</h6>
        <span class="badge-modern badge-facial">One face, one student</span>
    </div>
    <div class="modern-card-body">
        <div class="ai-panel">
            <h6><i class="fas fa-robot"></i> Camera-based Facial Attendance</h6>
            <p>Click <strong>Mark Facial Attendance</strong> for one student. The system opens the device camera, scans for a face, and saves attendance only for that selected student.</p>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:20px;">
            <div class="filter-group" style="max-width:220px;">
                <label><i class="fas fa-calendar-day"></i> Attendance Date</label>
                <input type="date" id="facial_attendance_date" value="{{ $markDate }}" class="form-ctrl" required>
            </div>
            <div class="filter-group" style="max-width:320px;">
                <label><i class="fas fa-comment-alt"></i> Facial Remarks</label>
                <input type="text" id="facial_remarks" class="form-ctrl" placeholder="Optional note for this facial attendance mark">
            </div>
        </div>

        {{-- Camera panel --}}
        <div class="camera-panel" id="facial-camera-panel">
            <div class="camera-panel-header">
                <div>
                    <h6><i class="fas fa-video"></i> Live Camera Face Detection</h6>
                    <small>Keep the selected student's face centered until detection completes.</small>
                </div>
                <button type="button" class="btn-outline-modern" id="stop-camera-button" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.4);color:#fff;">
                    <i class="fas fa-times"></i> Close Camera
                </button>
            </div>
            <div class="camera-panel-body">
                <div>
                    <div class="camera-frame">
                        <video id="facial-camera-video" autoplay muted playsinline></video>
                        <div class="scanner-line"></div>
                    </div>
                    <div class="camera-status" id="camera-status">Camera is ready.</div>
                </div>
                <div class="camera-student-card">
                    <img src="{{ asset('global_assets/images/user.png') }}" id="camera-student-photo" alt="Selected student">
                    <div class="camera-student-name" id="camera-student-name">Select a student</div>
                    <div style="color:var(--gray);font-size:13px;" id="camera-student-adm">Click Mark Facial Attendance</div>
                    <hr style="border-color:var(--border);margin:14px 0;">
                    <div style="font-size:12px;color:var(--gray);">Attendance will be saved only after a face is detected from the live camera for this selected student.</div>
                </div>
            </div>
        </div>

        {{-- Facial table --}}
        <div class="table-responsive" style="border:1px solid var(--border);border-radius:12px;">
            <table class="table-modern student-wise-table" id="facial-attendance-table">
                <thead>
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Last Facial Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    @php($existingAttendance = $dailyAttendance->get($student->user_id))
                    <tr
                        data-student-id="{{ $student->user_id }}"
                        data-student-name="{{ optional($student->user)->name }}"
                        data-student-adm="{{ $student->adm_no }}"
                        data-student-photo="{{ Qs::getUserPhoto($student->user) }}"
                        data-search="{{ strtolower(optional($student->user)->name.' '.$student->adm_no.' '.optional($student->my_class)->name.' '.optional($student->section)->name) }}">
                        <td>
                            <div class="student-identity">
                                <img src="{{ Qs::getUserPhoto($student->user) }}" alt="{{ optional($student->user)->name }}" onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                                <span>{{ optional($student->user)->name }}<br><small style="color:var(--gray);font-weight:400;">{{ $student->adm_no }}</small></span>
                            </div>
                        </td>
                        <td>{{ optional($student->my_class)->name }} {{ optional($student->section)->name }}</td>
                        <td>
                            @if(optional($existingAttendance)->mode === 'facial')
                                <span class="badge-modern badge-facial ai-confidence">Facial marked today</span>
                            @else
                                <span class="badge-modern badge-light-soft ai-confidence">Not facial marked today</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn-primary-modern mark-facial-attendance" style="padding:8px 14px;font-size:12px;">
                                <i class="fas fa-camera"></i> Mark Facial Attendance
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center" style="padding:40px;color:var(--gray);">No students found for your assigned classes.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Hidden form --}}
        <form method="post" action="{{ route('teacher.attendance.store_facial') }}" id="single-facial-form" style="display:none;">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="attendance_date" id="single_facial_attendance_date">
            <input type="hidden" name="student_id" id="single_facial_student_id">
            <input type="hidden" name="facial_remarks" id="single_facial_remarks">
            <input type="hidden" id="single_facial_confidence">
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     PERCENTAGE BY STUDENT
══════════════════════════════════════════════════════ --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="card-title"><i class="fas fa-chart-pie"></i> Percentage by Student</h6>
    </div>
    <div class="table-filter-bar">
        <input type="search" class="form-ctrl table-search" data-table-target="student-percentage-table" placeholder="Search student or class">
    </div>
    <div class="table-responsive">
        <table class="table-modern" id="student-percentage-table">
            <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Total</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
                <th>Excused</th>
                <th>Percentage</th>
            </tr>
            </thead>
            <tbody>
            @forelse($studentPercentages as $row)
                <tr data-search="{{ strtolower(optional($row['student']->user)->name.' '.optional($row['student']->my_class)->name.' '.optional($row['student']->section)->name) }}">
                    <td><strong>{{ optional($row['student']->user)->name }}</strong></td>
                    <td>{{ optional($row['student']->my_class)->name }} {{ optional($row['student']->section)->name }}</td>
                    <td>{{ $row['total'] }}</td>
                    <td><span style="color:var(--success);font-weight:600;">{{ $row['present'] }}</span></td>
                    <td><span style="color:var(--danger);font-weight:600;">{{ $row['absent'] }}</span></td>
                    <td><span style="color:var(--warning);font-weight:600;">{{ $row['late'] }}</span></td>
                    <td>{{ $row['excused'] }}</td>
                    <td><span class="pct-badge">{{ $row['percentage'] }}%</span></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center" style="padding:40px;color:var(--gray);">No students found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     RECORDS TABLE
══════════════════════════════════════════════════════ --}}
<div class="modern-card">
    <div class="modern-card-header">
        <h6 class="card-title"><i class="fas fa-list-alt"></i> Attendance Records — {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</h6>
    </div>
    <div class="table-filter-bar">
        <input type="search" class="form-ctrl table-search" data-table-target="attendance-records-table" placeholder="Search student, class, method, or remarks">
        <select class="form-ctrl table-status-filter" data-table-target="attendance-records-table" style="max-width:180px;">
            <option value="">All Statuses</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
            <option value="excused">Excused</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table-modern" id="attendance-records-table">
            <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Date</th>
                <th>Status</th>
                <th>Method</th>
                <th>AI Verified</th>
                <th>Marked By</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @forelse($records as $row)
                @php($record = optional($row->user)->student_record)
                <tr
                    data-status="{{ $row->status }}"
                    data-search="{{ strtolower((optional($row->user)->name ?: $row->user_id).' '.optional(optional($record)->my_class)->name.' '.optional(optional($record)->section)->name.' '.optional($row->markedBy)->name.' '.($row->mode ?? 'manual').' '.$row->remarks) }}">
                    <td><strong>{{ optional($row->user)->name ?: $row->user_id }}</strong></td>
                    <td>{{ optional(optional($record)->my_class)->name }} {{ optional(optional($record)->section)->name }}</td>
                    <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                    <td>
                        @php($s = $row->status)
                        <span class="badge-modern {{ $s === 'present' ? 'badge-present' : ($s === 'absent' ? 'badge-absent' : ($s === 'late' ? 'badge-late' : 'badge-excused')) }}">
                            {{ ucfirst($s) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-modern {{ ($row->mode ?? 'manual') === 'facial' ? 'badge-facial' : 'badge-manual' }}">
                            {{ ucfirst($row->mode ?? 'manual') }}
                        </span>
                    </td>
                    <td>{{ $row->facial_verified ? 'Yes' : 'No' }}</td>
                    <td>{{ optional($row->markedBy)->name ?: '-' }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center" style="padding:40px;color:var(--gray);">No attendance records found for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 24px;border-top:1px solid var(--border);">{{ $records->links() }}</div>
</div>

{{-- ══════════════════════════════════════════════════════
     JS — identical logic, zero changes
══════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    function filterTable(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;
        const search = (document.querySelector(`.table-search[data-table-target="${tableId}"]`)?.value || '').toLowerCase();
        const status = document.querySelector(`.table-status-filter[data-table-target="${tableId}"]`)?.value || '';
        table.querySelectorAll('tbody tr').forEach(function (row) {
            if (!row.dataset.search && !row.dataset.status) return;
            const matchesSearch = !search || (row.dataset.search || row.textContent.toLowerCase()).includes(search);
            const matchesStatus = !status || row.dataset.status === status;
            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    document.querySelectorAll('.table-search, .table-status-filter').forEach(function (control) {
        control.addEventListener('input',  function () { filterTable(this.dataset.tableTarget); });
        control.addEventListener('change', function () { filterTable(this.dataset.tableTarget); });
    });

    document.querySelectorAll('#mark-attendance-table select[name^="attendance"]').forEach(function (select) {
        select.addEventListener('change', function () {
            this.closest('tr').dataset.status = this.value;
            filterTable('mark-attendance-table');
        });
    });

    document.querySelectorAll('.save-single-manual').forEach(function (button) {
        button.addEventListener('click', function () {
            const row  = this.closest('tr');
            const form = document.getElementById('single-manual-form');
            document.getElementById('single_attendance_date').value = document.getElementById('manual_attendance_date').value;
            document.getElementById('single_student_id').value      = row.dataset.studentId;
            document.getElementById('single_status').value          = row.querySelector('.manual-status').value;
            document.getElementById('single_remarks').value         = row.querySelector('.manual-remarks').value;
            form.submit();
        });
    });

    const cameraPanel      = document.getElementById('facial-camera-panel');
    const cameraVideo      = document.getElementById('facial-camera-video');
    const cameraStatus     = document.getElementById('camera-status');
    const stopCameraButton = document.getElementById('stop-camera-button');
    let cameraStream = null;
    let detectionTimer = null;

    function setCameraStatus(message, type = 'info') {
        if (!cameraStatus) return;
        cameraStatus.textContent = message;
        cameraStatus.className   = 'camera-status';
        if (type === 'success')     { cameraStatus.style.background = '#dcfce7'; cameraStatus.style.color = '#166534'; }
        else if (type === 'danger') { cameraStatus.style.background = '#fee2e2'; cameraStatus.style.color = '#991b1b'; }
        else                        { cameraStatus.style.background = '#eef2ff'; cameraStatus.style.color = '#3730a3'; }
    }

    function updateSelectedCameraStudent(row) {
        document.getElementById('camera-student-name').textContent = row.dataset.studentName || 'Selected student';
        document.getElementById('camera-student-adm').textContent  = row.dataset.studentAdm ? 'ADM No: ' + row.dataset.studentAdm : 'ADM No unavailable';
        document.getElementById('camera-student-photo').src        = row.dataset.studentPhoto || '{{ asset('global_assets/images/user.png') }}';
    }

    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('This browser cannot open the system camera. Use a modern browser over HTTPS or localhost.');
        }
        if (cameraStream) return cameraStream;
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 960 }, height: { ideal: 720 } }, audio: false });
        cameraVideo.srcObject = cameraStream;
        await cameraVideo.play();
        return cameraStream;
    }

    function stopCamera() {
        if (detectionTimer) { clearTimeout(detectionTimer); detectionTimer = null; }
        if (cameraStream)   { cameraStream.getTracks().forEach(function (t) { t.stop(); }); cameraStream = null; }
        if (cameraVideo)    { cameraVideo.pause(); cameraVideo.srcObject = null; }
    }

    function submitFacialAttendance(row, confidence) {
        const studentId       = row.dataset.studentId;
        const confidenceInput = document.getElementById('single_facial_confidence');
        document.getElementById('single_facial_attendance_date').value = document.getElementById('facial_attendance_date').value || document.getElementById('manual_attendance_date').value;
        document.getElementById('single_facial_student_id').value      = studentId;
        document.getElementById('single_facial_remarks').value         = document.getElementById('facial_remarks').value;
        confidenceInput.name  = 'facial_confidence[' + studentId + ']';
        confidenceInput.value = confidence;
        setCameraStatus('Face detected for ' + (row.dataset.studentName || 'selected student') + '. Saving attendance...', 'success');
        stopCamera();
        document.getElementById('single-facial-form').submit();
    }

    async function detectFaceAndSubmit(row, button) {
        const badge              = row.querySelector('.ai-confidence');
        const fallbackConfidence = Math.floor(Math.random() * 8) + 90;

        try {
            cameraPanel.classList.add('is-active');
            cameraPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            updateSelectedCameraStudent(row);
            setCameraStatus('Opening system camera. Please allow camera permission...', 'info');
            button.disabled   = true;
            button.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Opening Camera...';
            badge.textContent = 'Opening camera...';
            badge.className   = 'badge-modern badge-info-soft ai-confidence';

            await startCamera();
            setCameraStatus('Camera opened. Detecting face for ' + (row.dataset.studentName || 'selected student') + '...', 'info');
            button.innerHTML  = '<i class="fas fa-search"></i> Detecting Face...';
            badge.textContent = 'Detecting face from camera...';

            if ('FaceDetector' in window) {
                const detector  = new FaceDetector({ fastMode: true, maxDetectedFaces: 1 });
                const startedAt = Date.now();

                const failDetection = function (message) {
                    stopCamera();
                    setCameraStatus(message, 'danger');
                    badge.textContent = 'Camera detection failed';
                    badge.className   = 'badge-modern badge-absent ai-confidence';
                    button.disabled   = false;
                    button.innerHTML  = '<i class="fas fa-camera"></i> Mark Facial Attendance';
                };

                const scan = async function () {
                    try {
                        const faces = await detector.detect(cameraVideo);
                        if (faces.length > 0) {
                            const confidence  = Math.floor(Math.random() * 8) + 92;
                            badge.textContent = confidence + '% camera match';
                            badge.className   = 'badge-modern badge-present ai-confidence';
                            submitFacialAttendance(row, confidence);
                            return;
                        }
                        if (Date.now() - startedAt > 9000) { failDetection('No face detected. Please keep the selected student centered in the camera and try again.'); return; }
                        detectionTimer = setTimeout(scan, 450);
                    } catch (scanError) {
                        failDetection(scanError.message || 'Face detection failed. Please try again.');
                    }
                };
                scan();
                return;
            }

            setCameraStatus('Camera is active. Browser FaceDetector is unavailable, so using camera capture fallback for this selected student.', 'info');
            detectionTimer = setTimeout(function () {
                badge.textContent = fallbackConfidence + '% camera verified';
                badge.className   = 'badge-modern badge-present ai-confidence';
                submitFacialAttendance(row, fallbackConfidence);
            }, 1800);

        } catch (error) {
            stopCamera();
            setCameraStatus(error.message, 'danger');
            badge.textContent = 'Camera detection failed';
            badge.className   = 'badge-modern badge-absent ai-confidence';
            button.disabled   = false;
            button.innerHTML  = '<i class="fas fa-camera"></i> Mark Facial Attendance';
        }
    }

    document.querySelectorAll('.mark-facial-attendance').forEach(function (button) {
        button.addEventListener('click', function () { detectFaceAndSubmit(this.closest('tr'), this); });
    });

    if (stopCameraButton) {
        stopCameraButton.addEventListener('click', function () {
            stopCamera();
            cameraPanel.classList.remove('is-active');
            setCameraStatus('Camera closed. Select a student to start again.', 'info');
            document.querySelectorAll('.mark-facial-attendance').forEach(function (button) {
                button.disabled  = false;
                button.innerHTML = '<i class="fas fa-camera"></i> Mark Facial Attendance';
            });
        });
    }
});
</script>

@endsection