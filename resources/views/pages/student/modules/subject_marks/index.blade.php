@extends('layouts.master')
@section('page_title', 'My Subject Marks')
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
    
    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.3);
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .hero-banner h4 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    
    .hero-banner h4 i {
        margin-right: 10px;
    }
    
    .hero-banner p {
        font-size: 14px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        margin: 0;
    }
    
    /* Filter Card */
    .filter-card {
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
    
    /* Summary Grid */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .summary-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
    }
    
    .summary-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
    }
    
    .summary-card span {
        font-size: 13px;
        color: var(--gray);
        font-weight: 500;
    }
    
    .summary-card .text-info { color: #0ea5e9; }
    .summary-card .text-primary { color: #4f46e5; }
    .summary-card .text-success { color: #10b981; }
    
    /* Table Card */
    .table-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
    
    /* Marks Pill */
    .mark-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #4f46e5;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        min-width: 65px;
    }
    
    .total-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }
    
    /* Footer */
    .card-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        background: white;
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
    @media (max-width: 1024px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }
    
    @media (max-width: 768px) {
        .hero-banner {
            padding: 20px 24px;
        }
        
        .hero-banner h4 {
            font-size: 18px;
        }
        
        .card-header {
            padding: 14px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .summary-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
    }
</style>

<div>
    <!-- Hero Banner -->
    <div class="hero-banner">
        <h4><i class="fas fa-chart-line"></i> My Mid & Final Subject Marks</h4>
        <p>View the mid and final marks your teachers have entered for each subject.</p>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="card-header">
            <h6 class="card-title"><i class="fas fa-filter"></i> Filter Marks</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('student.subject_marks.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label-modern"><i class="fas fa-calendar-alt"></i> Academic Year</label>
                    <input type="text" name="academic_year" value="{{ $academicYear }}" class="form-control-modern" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-primary-modern">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Grid -->
    <div class="summary-grid">
        <div class="summary-card">
            <h3>{{ $summary['subjects'] }}</h3>
            <span><i class="fas fa-book-open me-1"></i> Subjects Marked</span>
        </div>
        <div class="summary-card">
            <h3 class="text-info">{{ number_format($summary['mid_total'], 2) }}</h3>
            <span><i class="fas fa-chart-line me-1"></i> Total Mid Marks</span>
        </div>
        <div class="summary-card">
            <h3 class="text-primary">{{ number_format($summary['final_total'], 2) }}</h3>
            <span><i class="fas fa-chart-bar me-1"></i> Total Final Marks</span>
        </div>
        <div class="summary-card">
            <h3 class="text-success">{{ number_format($summary['overall_total'], 2) }}</h3>
            <span><i class="fas fa-star me-1"></i> Overall Total</span>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="card-header">
            <h6 class="card-title"><i class="fas fa-table"></i> Subject Marks for {{ $academicYear }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Class/Section</th>
                        <th>Teacher</th>
                        <th>Mid Marks</th>
                        <th>Final Marks</th>
                        <th>Total</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marks as $mark)
                        <tr>
                            <td><strong class="text-dark">{{ optional($mark->subject)->name }}</strong></td>
                            <td>{{ optional($mark->my_class)->name }} {{ optional($mark->section)->name }}</td>
                            <td>{{ optional($mark->teacher)->name ?: '-' }}</td>
                            <td>
                                @if($mark->mid_marks !== null)
                                    <span class="mark-pill">{{ number_format($mark->mid_marks, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($mark->final_marks !== null)
                                    <span class="mark-pill">{{ number_format($mark->final_marks, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="total-badge">{{ number_format($mark->total_marks, 2) }}</span>
                            </td>
                            <td>{{ $mark->remarks ?: '—' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-chart-line"></i>
                                    <p>No subject marks have been entered for this academic year yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($marks->hasPages())
            <div class="card-footer">
                {{ $marks->links() }}
            </div>
        @endif
    </div>
</div>

@endsection