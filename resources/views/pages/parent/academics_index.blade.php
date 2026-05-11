@extends('layouts.master')
@section('page_title', 'Children Academics')
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
    }
    
    .card-header h6 {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header h6 i {
        font-size: 18px;
        color: var(--primary);
    }
    
    /* Children Grid */
    .children-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        padding: 24px;
    }
    
    /* Child Card */
    .child-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s ease;
        text-align: center;
        padding: 24px 20px;
    }
    
    .child-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-light);
    }
    
    /* Child Avatar */
    .child-avatar {
        position: relative;
        display: inline-block;
        margin-bottom: 16px;
    }
    
    .child-avatar img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .child-card:hover .child-avatar img {
        transform: scale(1.05);
        border-color: var(--primary);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.2);
    }
    
    /* Child Name */
    .child-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
    }
    
    /* Child Class */
    .child-class {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        color: var(--primary);
        margin-bottom: 20px;
    }
    
    /* Stats Row */
    .stats-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
        padding: 12px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 20px;
        font-weight: 800;
        color: var(--dark);
    }
    
    .stat-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--gray);
        letter-spacing: 0.5px;
    }
    
    /* Action Button */
    .btn-academics {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 13px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-academics:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.35);
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        grid-column: 1 / -1;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
    
    .empty-state p {
        font-size: 16px;
        color: #94a3b8;
        margin-bottom: 8px;
    }
    
    .empty-state small {
        font-size: 13px;
        color: #cbd5e1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header {
            padding: 14px 20px;
        }
        
        .children-grid {
            gap: 16px;
            padding: 16px;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
        
        .child-card {
            padding: 20px 16px;
        }
        
        .child-avatar img {
            width: 70px;
            height: 70px;
        }
        
        .child-name {
            font-size: 16px;
        }
        
        .stats-row {
            gap: 16px;
        }
        
        .stat-number {
            font-size: 18px;
        }
    }
    
    @media (max-width: 480px) {
        .children-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-row {
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .stat-item {
            flex: 1;
            min-width: 70px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-book-open"></i> Choose Child - Academics
        </h6>
    </div>
    
    <div class="children-grid">
        @forelse($students as $s)
            @php
                // Get marks totals for this student
                $totals = $markTotals[$s->user_id] ?? null;
                $midTotal = number_format((float) ($totals->mid_total ?? 0), 2);
                $finalTotal = number_format((float) ($totals->final_total ?? 0), 2);
                $overallTotal = number_format((float) ($totals->overall_total ?? 0), 2);
            @endphp
            <div class="child-card">
                <div class="child-avatar">
                    <img src="{{ Qs::getUserPhoto($s->user) }}" alt="{{ $s->user->name }}" onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                </div>
                <h5 class="child-name">{{ $s->user->name }}</h5>
                <div class="child-class">
                    <i class="fas fa-school"></i> {{ optional($s->my_class)->name }} {{ optional($s->section)->name }}
                </div>
                
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-number" style="color: #4f46e5;">{{ $midTotal }}</div>
                        <div class="stat-label">Mid Marks</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" style="color: #10b981;">{{ $finalTotal }}</div>
                        <div class="stat-label">Final Marks</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" style="color: #f59e0b;">{{ $overallTotal }}</div>
                        <div class="stat-label">Overall</div>
                    </div>
                </div>
                
                <a href="{{ route('parent.academics.show', $s->id) }}" class="btn-academics">
                    <i class="fas fa-chart-line"></i> View Academics
                </a>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p>No children found</p>
                <small>Please contact the school administrator to add your children.</small>
            </div>
        @endforelse
    </div>
</div>

@endsection