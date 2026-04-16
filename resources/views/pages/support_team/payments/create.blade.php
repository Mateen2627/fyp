@extends('layouts.master')
@section('page_title', 'Create Payment')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-credit-card2 text-primary me-2"></i>
            Create New Payment
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row justify-content-center justify-content-md-start">
            <div class="col-12 col-lg-8">
                <!-- Payment Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="icon-plus-circle2 text-success me-2"></i>
                            Payment Details
                        </h6>
                    </div>
                    
                    <div class="card-body p-4">
                        <form class="ajax-store" method="post" action="{{ route('payments.store') }}" id="paymentForm">
                            @csrf
                            
                            <!-- Payment Title -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    Payment Title <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-tag text-muted"></i>
                                    </span>
                                    <input name="title" value="{{ old('title') }}" required type="text" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="e.g., School Fees, Tuition Fees, Exam Fees">
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="icon-info3 me-1"></i>
                                    Enter a descriptive title for this payment type
                                </small>
                            </div>

                            <!-- Class Selection -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    Class (Optional)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-users text-muted"></i>
                                    </span>
                                    <select class="form-control select-search" name="my_class_id" id="my_class_id">
                                        <option value="">All Classes</option>
                                        @foreach($my_classes as $c)
                                            <option {{ old('my_class_id') == $c->id ? 'selected' : '' }} 
                                                    value="{{ $c->id }}">
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="icon-info3 me-1"></i>
                                    Leave empty to apply to all classes
                                </small>
                            </div>

                            <!-- Payment Method -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="payment-method-option">
                                            <input type="radio" name="method" id="method_cash" value="Cash" 
                                                   class="d-none" checked>
                                            <label for="method_cash" class="method-label active">
                                                <i class="icon-cash3 me-2"></i>
                                                Cash Payment
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="payment-method-option">
                                            <input type="radio" name="method" id="method_online" value="Online" 
                                                   class="d-none" disabled>
                                            <label for="method_online" class="method-label disabled">
                                                <i class="icon-globe me-2"></i>
                                                Online Payment
                                                <span class="badge bg-secondary ms-2">Coming Soon</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    Amount <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-coin-dollar text-muted"></i>
                                    </span>
                                    <span class="input-group-text bg-light border-start-0 border-end-0">
                                        ₦
                                    </span>
                                    <input class="form-control border-start-0 ps-0" 
                                           value="{{ old('amount') }}" required name="amount" 
                                           id="amount" type="number" step="0.01" 
                                           placeholder="0.00">
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="icon-info3 me-1"></i>
                                    Enter the amount in Naira (₦)
                                </small>
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    Description (Optional)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-file-text2 text-muted"></i>
                                    </span>
                                    <textarea name="description" id="description" 
                                              class="form-control border-start-0 ps-0" 
                                              rows="3" 
                                              placeholder="Enter additional details about this payment...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Payment Summary Card -->
                            <div class="payment-summary bg-light rounded-3 p-3 mb-4">
                                <h6 class="fw-semibold mb-2">
                                    <i class="icon-info-circle me-1"></i>
                                    Payment Summary
                                </h6>
                                <div class="summary-details">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Payment Title:</span>
                                        <span class="fw-semibold" id="summaryTitle">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Class:</span>
                                        <span class="fw-semibold" id="summaryClass">All Classes</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Method:</span>
                                        <span class="fw-semibold text-success" id="summaryMethod">Cash</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Amount:</span>
                                        <span class="fw-bold text-primary" id="summaryAmount">₦0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-light" onclick="resetForm()">
                                    <i class="icon-reset me-2"></i>
                                    Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                    <i class="icon-paperplane me-2"></i>
                                    Create Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar (Optional) -->
            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="icon-info-circle text-info me-2"></i>
                            Important Notes
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex">
                                <i class="icon-checkmark-circle text-success me-2 mt-1"></i>
                                <span class="small">All payment records are automatically tracked in the system</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <i class="icon-checkmark-circle text-success me-2 mt-1"></i>
                                <span class="small">You can assign payments to specific classes or make them available to all</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <i class="icon-checkmark-circle text-success me-2 mt-1"></i>
                                <span class="small">Payment descriptions help parents understand what the fee covers</span>
                            </li>
                            <li class="d-flex">
                                <i class="icon-checkmark-circle text-success me-2 mt-1"></i>
                                <span class="small">Online payment integration coming soon for seamless transactions</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Payment Method Styling */
.payment-method-option .method-label {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1rem;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    font-weight: 500;
}

.payment-method-option .method-label.active {
    border-color: #28a745;
    background: rgba(40,167,69,0.05);
    color: #28a745;
}

.payment-method-option .method-label.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: #f8f9fa;
}

.payment-method-option .method-label:not(.disabled):hover {
    border-color: #28a745;
    transform: translateY(-2px);
}

/* Payment Summary */
.payment-summary {
    background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
    border-left: 4px solid #667eea;
}

/* Form Elements */
.input-group-text {
    background: #f8f9fa;
    border-right: none;
    padding: 0.5rem 0.75rem;
}

.input-group .form-control {
    border-left: none;
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.input-group .form-control:focus {
    background: #fff;
    border-color: #ced4da;
    box-shadow: none;
}

.input-group:focus-within .input-group-text {
    border-color: #007bff;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Button Styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
}

.btn-light {
    border: 1px solid #dee2e6;
    background: white;
    transition: all 0.3s ease;
}

.btn-light:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1.25rem !important;
    }
    
    .form-label {
        font-size: 0.875rem;
    }
    
    .payment-method-option .method-label {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .payment-summary {
        padding: 1rem !important;
    }
    
    .summary-details {
        font-size: 0.875rem;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        padding: 1rem !important;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .input-group-text {
        padding: 0.375rem 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
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

.card {
    animation: fadeIn 0.5s ease forwards;
}

/* Loading State */
.btn-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.8;
}

.btn-loading:after {
    content: '';
    position: absolute;
    width: 1rem;
    height: 1rem;
    top: 50%;
    left: 50%;
    margin-left: -0.5rem;
    margin-top: -0.5rem;
    border: 2px solid white;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spin 0.5s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Select2 Custom Styling */
.select-search + .select2-container .select2-selection--single {
    border-radius: 0.5rem !important;
    border: 1px solid #dee2e6 !important;
    height: 42px !important;
    transition: all 0.2s ease;
}

.select-search + .select2-container .select2-selection--single:hover {
    border-color: #007bff !important;
}

.select-search + .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding-left: 1rem !important;
}

.select2-dropdown {
    border-radius: 0.5rem !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}

/* Print Styles */
@media print {
    .navbar, .card-header button, .btn, .info-sidebar {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select-search').select2({
            width: '100%',
            allowClear: true,
            placeholder: 'Select an option'
        });
    }
    
    // Live preview updates
    const titleInput = document.querySelector('input[name="title"]');
    const classSelect = document.querySelector('#my_class_id');
    const amountInput = document.querySelector('#amount');
    
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            document.getElementById('summaryTitle').textContent = this.value || '-';
        });
    }
    
    if (classSelect) {
        classSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const className = selectedOption.text || 'All Classes';
            document.getElementById('summaryClass').textContent = className;
        });
    }
    
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            document.getElementById('summaryAmount').textContent = '₦' + amount.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    }
    
    // Payment method selection
    const methodRadios = document.querySelectorAll('input[name="method"]');
    methodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const methodLabel = document.querySelector(`label[for="${this.id}"]`);
            document.querySelectorAll('.method-label').forEach(label => {
                label.classList.remove('active');
            });
            if (methodLabel) {
                methodLabel.classList.add('active');
                document.getElementById('summaryMethod').textContent = this.value;
            }
        });
    });
    
    // Form submission loading state
    const form = document.getElementById('paymentForm');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
        });
    }
});

// Reset form function
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        const form = document.getElementById('paymentForm');
        form.reset();
        
        // Reset Select2
        $('#my_class_id').val('').trigger('change');
        
        // Reset summary
        document.getElementById('summaryTitle').textContent = '-';
        document.getElementById('summaryClass').textContent = 'All Classes';
        document.getElementById('summaryMethod').textContent = 'Cash';
        document.getElementById('summaryAmount').textContent = '₦0.00';
        
        // Reset active method label
        document.querySelectorAll('.method-label').forEach(label => {
            label.classList.remove('active');
        });
        document.querySelector('label[for="method_cash"]').classList.add('active');
        
        // Remove loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.classList.remove('btn-loading');
        submitBtn.disabled = false;
    }
}

// Number formatting helper
function formatNumber(number) {
    return new Intl.NumberFormat('en-NG', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(number);
}
</script>
@endsection