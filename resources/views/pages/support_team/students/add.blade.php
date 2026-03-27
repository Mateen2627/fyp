@extends('layouts.master')
@section('page_title', 'Admit Student')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-user-plus text-primary me-2"></i>
            Admit New Student
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <form id="ajax-reg" method="post" enctype="multipart/form-data" 
              class="wizard-form steps-validation" action="{{ route('students.store') }}" data-fouc>
            @csrf
            
            <!-- Personal Data Section -->
            <div class="section-card mb-4">
                <div class="section-header bg-light py-3 px-4 rounded-top">
                    <h6 class="fw-bold mb-0">
                        <i class="icon-user text-primary me-2"></i>
                        Personal Information
                    </h6>
                </div>
                
                <div class="section-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-user text-muted"></i>
                                    </span>
                                    <input value="{{ old('name') }}" required type="text" 
                                           name="name" placeholder="Enter full name" 
                                           class="form-control border-start-0 ps-0">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-location4 text-muted"></i>
                                    </span>
                                    <input value="{{ old('address') }}" class="form-control border-start-0 ps-0" 
                                           placeholder="Enter residential address" name="address" 
                                           type="text" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-envelope text-muted"></i>
                                    </span>
                                    <input type="email" value="{{ old('email') }}" name="email" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="student@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Gender <span class="text-danger">*</span>
                                </label>
                                <select class="select form-control" id="gender" name="gender" 
                                        required data-placeholder="Select gender">
                                    <option value=""></option>
                                    <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                    <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-phone text-muted"></i>
                                    </span>
                                    <input value="{{ old('phone') }}" type="text" name="phone" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Alternative Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-phone2 text-muted"></i>
                                    </span>
                                    <input value="{{ old('phone2') }}" type="text" name="phone2" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="Alternative number">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-calendar text-muted"></i>
                                    </span>
                                    <input name="dob" value="{{ old('dob') }}" type="text" 
                                           class="form-control border-start-0 ps-0 date-pick" 
                                           placeholder="Select date of birth">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Nationality <span class="text-danger">*</span>
                                </label>
                                <select data-placeholder="Select nationality" required name="nal_id" 
                                        id="nal_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($nationals as $nal)
                                        <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} 
                                                value="{{ $nal->id }}">{{ $nal->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    State <span class="text-danger">*</span>
                                </label>
                                <select onchange="getLGA(this.value)" required data-placeholder="Select state" 
                                        class="select-search form-control" name="state_id" id="state_id">
                                    <option value=""></option>
                                    @foreach($states as $st)
                                        <option {{ (old('state_id') == $st->id ? 'selected' : '') }} 
                                                value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    LGA <span class="text-danger">*</span>
                                </label>
                                <select required data-placeholder="Select state first" 
                                        class="select-search form-control" name="lga_id" id="lga_id">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Blood Group</label>
                                <select class="select form-control" id="bg_id" name="bg_id" 
                                        data-placeholder="Select blood group (optional)">
                                    <option value=""></option>
                                    @foreach(App\Models\BloodGroup::all() as $bg)
                                        <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} 
                                                value="{{ $bg->id }}">{{ $bg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold d-block">Passport Photograph</label>
                                <div class="custom-file-upload">
                                    <input value="{{ old('photo') }}" accept="image/*" type="file" 
                                           name="photo" class="form-input-styled" data-fouc>
                                    <span class="form-text text-muted small">
                                        <i class="icon-info3 me-1"></i>
                                        Accepted: jpeg, png. Max size: 2MB
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Data Section -->
            <div class="section-card mb-4">
                <div class="section-header bg-light py-3 px-4 rounded-top">
                    <h6 class="fw-bold mb-0">
                        <i class="icon-graduation text-primary me-2"></i>
                        Academic Information
                    </h6>
                </div>
                
                <div class="section-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Class <span class="text-danger">*</span>
                                </label>
                                <select onchange="getClassSections(this.value)" data-placeholder="Select class" 
                                        required name="my_class_id" id="my_class_id" 
                                        class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($my_classes as $c)
                                        <option {{ (old('my_class_id') == $c->id ? 'selected' : '') }} 
                                                value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Section <span class="text-danger">*</span>
                                </label>
                                <select data-placeholder="Select class first" required 
                                        name="section_id" id="section_id" class="select-search form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Parent/Guardian</label>
                                <select data-placeholder="Select parent (optional)" name="my_parent_id" 
                                        id="my_parent_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($parents as $p)
                                        <option {{ (old('my_parent_id') == Qs::hash($p->id)) ? 'selected' : '' }} 
                                                value="{{ Qs::hash($p->id) }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">
                                    Year Admitted <span class="text-danger">*</span>
                                </label>
                                <select data-placeholder="Select year" required name="year_admitted" 
                                        id="year_admitted" class="select-search form-control">
                                    <option value=""></option>
                                    @for($y=date('Y', strtotime('- 10 years')); $y<=date('Y'); $y++)
                                        <option {{ (old('year_admitted') == $y) ? 'selected' : '' }} 
                                                value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Dormitory</label>
                                <select data-placeholder="Select dormitory (optional)" name="dorm_id" 
                                        id="dorm_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($dorms as $d)
                                        <option {{ (old('dorm_id') == $d->id) ? 'selected' : '' }} 
                                                value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Room Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-door text-muted"></i>
                                    </span>
                                    <input type="text" name="dorm_room_no" placeholder="Room number" 
                                           class="form-control border-start-0 ps-0" 
                                           value="{{ old('dorm_room_no') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Sports House</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-trophy text-muted"></i>
                                    </span>
                                    <input type="text" name="house" placeholder="Sports house" 
                                           class="form-control border-start-0 ps-0" 
                                           value="{{ old('house') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold">Admission Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-vcard text-muted"></i>
                                    </span>
                                    <input type="text" name="adm_no" placeholder="Auto-generated if empty" 
                                           class="form-control border-start-0 ps-0" 
                                           value="{{ old('adm_no') }}">
                                </div>
                                <small class="text-muted">Leave empty for auto-generation</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="button" class="btn btn-light" onclick="resetForm()">
                    <i class="icon-reset me-2"></i>
                    Reset Form
                </button>
                <div>
                    <button type="submit" class="btn btn-primary px-4" id="submit-btn">
                        <i class="icon-paperplane me-2"></i>
                        Admit Student
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Section Card Styling */
.section-card {
    border: 1px solid rgba(0,0,0,0.05);
    border-radius: 0.75rem;
    overflow: hidden;
    background: white;
}

.section-header {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

/* Form Element Styling */
.form-group {
    margin-bottom: 0;
}

.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.375rem;
    color: #495057;
}

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

/* Select Styling */
.select2-container--default .select2-selection--single {
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    height: 42px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
}

/* File Upload Styling */
.custom-file-upload {
    position: relative;
}

.form-input-styled {
    padding: 0.5rem 0;
    width: 100%;
}

/* Button Styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-light {
    border: 1px solid #dee2e6;
    background: white;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .section-body {
        padding: 1.25rem !important;
    }
    
    .form-label {
        font-size: 0.8125rem;
    }
    
    .input-group-text, .form-control {
        font-size: 0.875rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 575.98px) {
    .card-header {
        padding: 1rem !important;
    }
    
    .section-body {
        padding: 1rem !important;
    }
    
    .row {
        margin: -0.5rem;
    }
    
    .col-12 {
        padding: 0.5rem;
    }
    
    .input-group-text {
        padding: 0.375rem 0.5rem;
    }
}

/* Animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.section-card {
    animation: slideIn 0.5s ease forwards;
}

.section-card:nth-child(2) {
    animation-delay: 0.1s;
}

/* Required Field Indicator */
.text-danger {
    font-size: 1.2em;
    line-height: 1;
}

/* Success Message Styling */
.alert-success {
    border-left: 4px solid #28a745;
    border-radius: 0.5rem;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select-search').select2({
            width: '100%',
            allowClear: true,
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option';
            }
        });
        
        $('.select').select2({
            width: '100%',
            minimumResultsForSearch: -1,
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option';
            }
        });
    }
    
    // Date picker initialization (if using bootstrap-datepicker)
    if ($.fn.datepicker) {
        $('.date-pick').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }
    
    // Form submission loading state
    $('#ajax-reg').on('submit', function() {
        $('#submit-btn').addClass('btn-loading').prop('disabled', true);
    });
});

// Reset form function
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        document.getElementById('ajax-reg').reset();
        $('.select-search').val('').trigger('change');
        $('.select').val('').trigger('change');
        $('#lga_id').empty().trigger('change');
        $('#section_id').empty().trigger('change');
        $('#submit-btn').removeClass('btn-loading').prop('disabled', false);
    }
}
</script>

@endsection