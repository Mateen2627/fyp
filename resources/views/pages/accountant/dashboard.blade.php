@extends('layouts.master')
@section('page_title', 'Accountant Dashboard')
@section('content')

<!-- Welcome Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="welcome-icon me-3">
                <i class="icon-calculator text-primary" style="font-size: 2.5rem;"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1">Accountant Dashboard</h5>
                <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
        </div>
        <div class="stats-badge">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                <i class="icon-calendar me-1"></i>
                {{ date('l, F j, Y') }}
            </span>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="alert alert-info bg-info bg-opacity-10 border-info border-start border-4 rounded-3 mb-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="icon-info3 text-info fs-4 me-3"></i>
                <div>
                    <strong class="d-block mb-1">Fee Management System</strong>
                    <span class="small">Manage fees, create invoices, track student payments, and generate financial reports.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card stat-card bg-gradient-primary border-0 shadow-sm h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-80 small text-uppercase tracking-wide">Total Students</span>
                        <h2 class="text-white fw-bold mb-0 mt-2">{{ $totalStudents ?? 'N/A' }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="icon-users4 icon-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card stat-card bg-gradient-success border-0 shadow-sm h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-80 small text-uppercase tracking-wide">Total Payments</span>
                        <h2 class="text-white fw-bold mb-0 mt-2">₦{{ number_format($totalPayments ?? 0, 2) }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="icon-coin-dollar icon-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card stat-card bg-gradient-warning border-0 shadow-sm h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-80 small text-uppercase tracking-wide">Pending Fees</span>
                        <h2 class="text-white fw-bold mb-0 mt-2">₦{{ number_format($pendingFees ?? 0, 2) }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="icon-hourglass2 icon-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Cards -->
<div class="row g-4">
    <!-- Create Fee Module -->
    <div class="col-12 col-md-4">
        <div class="card action-card border-0 shadow-sm h-100 text-center">
            <div class="card-body p-4">
                <div class="action-icon mx-auto mb-3">
                    <i class="icon-plus-circle3 icon-3x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-2">Create Fee Module</h5>
                <p class="text-muted small mb-3">Set up new fee structures, tuition fees, and other charges for students</p>
                <a href="{{ route('payments.create') }}" class="btn btn-outline-primary stretched-link">
                    <i class="icon-add me-2"></i>
                    Create Fee Module
                </a>
            </div>
        </div>
    </div>
    
    <!-- Manage Fee Types Module -->
    <div class="col-12 col-md-4">
        <div class="card action-card border-0 shadow-sm h-100 text-center">
            <div class="card-body p-4">
                <div class="action-icon mx-auto mb-3">
                    <i class="icon-list3 icon-3x text-success"></i>
                </div>
                <h5 class="fw-bold mb-2">Manage Fee Types</h5>
                <p class="text-muted small mb-3">Configure and manage different fee categories like tuition, library, sports, etc.</p>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-success stretched-link">
                    <i class="icon-cog3 me-2"></i>
                    Manage Fee Types
                </a>
            </div>
        </div>
    </div>
    
    <!-- Student Payments Module -->
    <div class="col-12 col-md-4">
        <div class="card action-card border-0 shadow-sm h-100 text-center">
            <div class="card-body p-4">
                <div class="action-icon mx-auto mb-3">
                    <i class="icon-credit-card2 icon-3x text-warning"></i>
                </div>
                <h5 class="fw-bold mb-2">Student Payments</h5>
                <p class="text-muted small mb-3">Record student payments, generate invoices, and track payment history</p>
                <a href="{{ route('payments.manage') }}" class="btn btn-outline-warning stretched-link">
                    <i class="icon-dollar me-2"></i>
                    Manage Payments
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Section (Optional) -->
@if(isset($recentTransactions) && count($recentTransactions) > 0)
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">
            <i class="icon-history text-primary me-2"></i>
            Recent Transactions
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-3">Date</th>
                        <th class="px-3">Student</th>
                        <th class="px-3">Fee Type</th>
                        <th class="px-3">Amount</th>
                        <th class="px-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td class="px-3">{{ $transaction->created_at->format('d M, Y') }}</td>
                        <td class="px-3">{{ $transaction->student->name ?? 'N/A' }}</td>
                        <td class="px-3">{{ $transaction->feeType->name ?? 'N/A' }}</td>
                        <td class="px-3 fw-semibold text-success">₦{{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-3">
                            <span class="badge bg-success bg-opacity-10 text-success">Completed</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<style>
/* Welcome Section */
.welcome-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    border-radius: 1rem;
}

/* Stat Cards */
.stat-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border-radius: 1rem !important;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
    border-radius: 1rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.text-white-80 {
    color: rgba(255,255,255,0.9);
}

.tracking-wide {
    letter-spacing: 0.5px;
    font-weight: 500;
}

/* Action Cards */
.action-card {
    transition: all 0.3s ease;
    border-radius: 1rem !important;
    cursor: pointer;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.action-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.action-card:hover .action-icon {
    transform: scale(1.1);
}

/* Buttons */
.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

.btn-outline-success {
    border: 2px solid #28a745;
    color: #28a745;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    background: #28a745;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40,167,69,0.3);
}

.btn-outline-warning {
    border: 2px solid #ffc107;
    color: #ffc107;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.btn-outline-warning:hover {
    background: #ffc107;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,193,7,0.3);
}

.stretched-link {
    position: relative;
    z-index: 1;
}

/* Alert Styling */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .welcome-icon {
        width: 50px;
        height: 50px;
    }
    
    .welcome-icon i {
        font-size: 1.75rem !important;
    }
    
    .stat-card .card-body {
        padding: 1rem !important;
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
    }
    
    .stat-icon i {
        font-size: 1.5rem !important;
    }
    
    .action-card .card-body {
        padding: 1.5rem !important;
    }
    
    .action-icon {
        width: 60px;
        height: 60px;
    }
    
    .action-icon i {
        font-size: 2rem !important;
    }
    
    .action-card h5 {
        font-size: 1rem;
    }
    
    .action-card p {
        font-size: 0.813rem;
    }
    
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .stats-badge {
        margin-top: 0.5rem;
    }
    
    .stat-card h2 {
        font-size: 1.5rem;
    }
    
    .stat-card .text-uppercase {
        font-size: 0.7rem;
    }
    
    .action-card h5 {
        font-size: 0.95rem;
    }
    
    .action-icon {
        width: 50px;
        height: 50px;
    }
    
    .action-icon i {
        font-size: 1.5rem !important;
    }
}

/* Animation */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.row > [class*="col-"] {
    animation: slideInUp 0.5s ease forwards;
    opacity: 0;
}

.row > [class*="col-"]:nth-child(1) { animation-delay: 0.1s; }
.row > [class*="col-"]:nth-child(2) { animation-delay: 0.2s; }
.row > [class*="col-"]:nth-child(3) { animation-delay: 0.3s; }

/* Print Styles */
@media print {
    .navbar, .stats-badge, .alert-info, .btn {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}

/* Hover Effects */
.stat-card, .action-card {
    will-change: transform;
}

/* Gradient Text */
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation for stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
    
    // Animate numbers if you have count-up functionality
    function animateNumber(element, target) {
        if (!element) return;
        
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 20);
    }
    
    // Example usage (uncomment if you have numeric values)
    // const totalPayments = document.querySelector('.stat-card .total-payments');
    // if (totalPayments) {
    //     animateNumber(totalPayments, {{ $totalPayments ?? 0 }});
    // }
});
</script>
@endsection