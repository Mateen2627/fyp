@extends('layouts.master')
@section('page_title', 'Attendance')
@section('content')

<style>
    /* Modern Professional Design */
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
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }
    
    /* Container */
    .attendance-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        border-radius: 24px;
        padding: 28px 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    
    .welcome-subtitle {
        font-size: 14px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }
    
    .stat-item {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stat-icon.blue { background: #eef2ff; color: #4f46e5; }
    .stat-icon.green { background: #ecfdf5; color: #10b981; }
    .stat-icon.orange { background: #fffbeb; color: #f59e0b; }
    .stat-icon.red { background: #fef2f2; color: #ef4444; }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--gray);
        font-weight: 500;
        margin-top: 4px;
    }
    
    /* Cards */
    .card-modern {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        margin-bottom: 28px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .card-modern:hover {
        box-shadow: var(--shadow-md);
    }
    
    .card-header-modern {
        padding: 20px 28px;
        background: var(--light);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    
    .card-title i {
        color: var(--primary);
        font-size: 20px;
    }
    
    .card-body-modern {
        padding: 28px;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .form-label i {
        margin-right: 6px;
        color: var(--primary);
    }
    
    .form-control-custom {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control-custom:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    select.form-control-custom {
        cursor: pointer;
    }
    
    /* Buttons */
    .btn-primary-custom {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
    }
    
    .btn-secondary-custom {
        background: #f1f5f9;
        border: none;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        color: var(--gray);
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-secondary-custom:hover {
        background: #e2e8f0;
    }
    
    /* Table */
    .table-container {
        overflow-x: auto;
    }
    
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-custom thead th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border-bottom: 2px solid var(--border);
        text-align: left;
    }
    
    .table-custom tbody td {
        padding: 14px 16px;
        font-size: 14px;
        color: var(--dark);
        border-bottom: 1px solid var(--border);
    }
    
    .table-custom tbody tr:hover {
        background: var(--light);
    }
    
    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-success { background: #ecfdf5; color: #059669; }
    .badge-danger { background: #fef2f2; color: #dc2626; }
    .badge-warning { background: #fffbeb; color: #d97706; }
    .badge-info { background: #eef2ff; color: #4f46e5; }
    .badge-gray { background: #f1f5f9; color: #475569; }
    
    /* Alert */
    .alert-custom {
        padding: 14px 18px;
        border-radius: 14px;
        background: #fefce8;
        border-left: 3px solid #eab308;
        font-size: 13px;
        margin-top: 16px;
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
        color: #94a3b8;
        margin-bottom: 8px;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }
    
    @media (max-width: 768px) {
        .welcome-banner {
            padding: 20px 24px;
        }
        
        .welcome-title {
            font-size: 20px;
        }
        
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .card-body-modern {
            padding: 20px;
        }
        
        .stats-grid {
            gap: 12px;
        }
        
        .stat-value {
            font-size: 24px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .btn-primary-custom {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="attendance-container">
    
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-title">
            <i class="fas fa-calendar-check me-2"></i> Attendance Management
        </div>
        <div class="welcome-subtitle">
            Track and manage your daily attendance with facial recognition technology
        </div>
    </div>
    
    <!-- Stats Cards -->
    @php
        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();
        $attendanceRate = $total > 0 ? round(($present / $total) * 100) : 0;
    @endphp
    
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">Total School Days</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-header">
                <div class="stat-icon green">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $present }}</div>
            <div class="stat-label">Days Present</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-value">{{ $attendanceRate }}%</div>
            <div class="stat-label">Attendance Rate</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-header">
                <div class="stat-icon red">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">{{ $late }}</div>
            <div class="stat-label">Late Arrivals</div>
        </div>
    </div>
    
    <!-- Face Registration Card -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h4 class="card-title">
                <i class="fas fa-fingerprint"></i> Face Recognition Setup
            </h4>
            <span class="badge badge-info">
                <i class="fas fa-microchip"></i> AI Powered
            </span>
        </div>
        <div class="card-body-modern">
            <form action="{{ route('student.attendance.register_face') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-key"></i> Face Token
                            </label>
                            <input type="text" name="face_token" class="form-control-custom" 
                                   value="{{ old('face_token', optional($faceProfile)->face_token) }}" 
                                   placeholder="Enter unique face embedding token">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i> Reference Image
                            </label>
                            <input type="file" name="face_image" class="form-control-custom" accept="image/*">
                            <small class="text-muted" style="font-size: 11px;">Upload a clear photo of your face</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn-secondary-custom w-100">
                                <i class="fas fa-save"></i> Register
                            </button>
                        </div>
                    </div>
                </div>
                @if($faceProfile)
                    <div class="alert-custom">
                        <i class="fas fa-check-circle me-2"></i> 
                        Your face profile is active and ready for facial recognition attendance.
                    </div>
                @endif
            </form>
        </div>
    </div>
    
    <!-- Mark Attendance Card -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h4 class="card-title">
                <i class="fas fa-camera"></i> Mark Attendance
            </h4>
            <span class="badge badge-success">
                <i class="fas fa-check-circle"></i> Today: {{ now()->format('d M Y') }}
            </span>
        </div>
        <div class="card-body-modern">
            <form action="{{ route('student.attendance.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-day"></i> Date
                            </label>
                            <input type="date" name="attendance_date" class="form-control-custom" 
                                   value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag-checkered"></i> Status
                            </label>
                            <select name="status" class="form-control-custom" required>
                                <option value="present">✅ Present</option>
                                <option value="absent">❌ Absent</option>
                                <option value="late">⏰ Late</option>
                                <option value="excused">📝 Excused</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-microchip"></i> Verification Mode
                            </label>
                            <select name="mode" id="attendance_mode" class="form-control-custom" required>
                                <option value="manual">✍️ Manual Entry</option>
                                <option value="facial">🤖 AI Facial Recognition</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="face_token_box" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-fingerprint"></i> Face Verification
                            </label>
                            <input type="text" name="face_token" class="form-control-custom" 
                                   placeholder="Enter your face token">
                            <small class="text-muted" style="font-size: 11px;">Required for facial recognition</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-pen"></i> Remarks (Optional)
                            </label>
                            <input type="text" name="remarks" class="form-control-custom" 
                                   placeholder="Add any notes or comments">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-save"></i> Save Attendance Record
                </button>
            </form>
        </div>
    </div>
    
    <!-- Attendance History Card -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h4 class="card-title">
                <i class="fas fa-history"></i> Attendance History
            </h4>
            <span class="badge badge-gray">
                <i class="fas fa-chart-simple"></i> {{ $total }} Records
            </span>
        </div>
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar me-1"></i> Date</th>
                        <th><i class="fas fa-flag-checkered me-1"></i> Status</th>
                        <th><i class="fas fa-microchip me-1"></i> Method</th>
                        <th><i class="fas fa-shield-alt me-1"></i> Verification</th>
                        <th><i class="fas fa-comment me-1"></i> Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $row)
                        <tr>
                            <td>
                                <strong>{{ optional($row->attendance_date)->format('d M Y') }}</strong>
                                <div class="text-muted" style="font-size: 11px;">{{ optional($row->attendance_date)->format('l') }}</div>
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
                                        default => 'fa-calendar-check'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $row->mode == 'facial' ? 'badge-info' : 'badge-gray' }}">
                                    <i class="fas {{ $row->mode == 'facial' ? 'fa-microchip' : 'fa-pen' }}"></i>
                                    {{ ucfirst($row->mode ?? 'manual') }}
                                </span>
                            </td>
                            <td>
                                @if($row->facial_verified ?? 0)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-double"></i> Verified
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $row->remarks ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>No attendance records found</p>
                                    <small class="text-muted">Start by marking your attendance above</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
            <div class="card-header-modern border-top">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modeSelect = document.getElementById('attendance_mode');
        const faceTokenBox = document.getElementById('face_token_box');
        
        function toggleFaceToken() {
            if (modeSelect && faceTokenBox) {
                faceTokenBox.style.display = modeSelect.value === 'facial' ? 'block' : 'none';
            }
        }
        
        if (modeSelect) {
            modeSelect.addEventListener('change', toggleFaceToken);
            toggleFaceToken();
        }
    });
</script>

@endsection