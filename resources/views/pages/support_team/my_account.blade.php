@extends('layouts.master')
@section('page_title', 'My Account')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-user text-primary me-2"></i>
            My Account
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-highlight flex-nowrap flex-sm-wrap overflow-auto pb-2 pb-sm-0 mb-4" role="tablist">
            <li class="nav-item me-1">
                <a href="#change-pass" class="nav-link active px-3 px-sm-4" data-toggle="tab" role="tab">
                    <i class="icon-lock me-1 d-none d-sm-inline"></i>
                    Change Password
                </a>
            </li>
            @if(Qs::userIsPTA() || Qs::userIsStudent())
                <li class="nav-item ms-auto">
                    <a href="#edit-profile" class="nav-link px-3 px-sm-4" data-toggle="tab" role="tab">
                        <i class="icon-user-edit me-1 d-none d-sm-inline"></i>
                        Manage Profile
                    </a>
                </li>
            @endif
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Change Password Tab -->
            <div class="tab-pane fade show active" id="change-pass" role="tabpanel">
                <div class="row justify-content-center justify-content-md-start">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="icon-key text-danger me-2"></i>
                                    Update Your Password
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form method="post" action="{{ route('my_account.change_pass') }}" id="changePasswordForm">
                                    @csrf @method('put')

                                    <!-- Current Password -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">
                                            Current Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-lock text-muted"></i>
                                            </span>
                                            <input id="current_password" name="current_password" 
                                                   required type="password" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Enter current password">
                                            <button class="btn btn-light border" type="button" 
                                                    onclick="togglePassword('current_password', this)">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- New Password -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">
                                            New Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-key text-muted"></i>
                                            </span>
                                            <input id="password" name="password" 
                                                   required type="password" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Enter new password">
                                            <button class="btn btn-light border" type="button" 
                                                    onclick="togglePassword('password', this)">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                            </div>
                                            <small class="text-muted">Use at least 8 characters with letters and numbers</small>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">
                                            Confirm Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-checkmark-circle text-muted"></i>
                                            </span>
                                            <input id="password_confirmation" name="password_confirmation" 
                                                   required type="password" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Re-enter new password">
                                            <button class="btn btn-light border" type="button" 
                                                    onclick="togglePassword('password_confirmation', this)">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-match mt-2 d-none">
                                            <small class="text-success"><i class="icon-checkmark-circle"></i> Passwords match</small>
                                        </div>
                                    </div>

                                    <!-- Password Requirements -->
                                    <div class="alert alert-light bg-light border-0 rounded-3 p-3 mb-4">
                                        <h6 class="fw-semibold mb-2 small text-uppercase text-muted">Password Requirements:</h6>
                                        <ul class="list-unstyled mb-0 small">
                                            <li class="mb-1 requirement" data-requirement="length">
                                                <i class="icon-circle text-muted me-2"></i> At least 8 characters
                                            </li>
                                            <li class="mb-1 requirement" data-requirement="uppercase">
                                                <i class="icon-circle text-muted me-2"></i> At least one uppercase letter
                                            </li>
                                            <li class="mb-1 requirement" data-requirement="lowercase">
                                                <i class="icon-circle text-muted me-2"></i> At least one lowercase letter
                                            </li>
                                            <li class="mb-1 requirement" data-requirement="number">
                                                <i class="icon-circle text-muted me-2"></i> At least one number
                                            </li>
                                            <li class="requirement" data-requirement="match">
                                                <i class="icon-circle text-muted me-2"></i> Passwords match
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-danger px-4" id="changePassBtn">
                                            <i class="icon-paperplane me-2"></i>
                                            Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Tab -->
            @if(Qs::userIsPTA() || Qs::userIsStudent())
            <div class="tab-pane fade" id="edit-profile" role="tabpanel">
                <div class="row justify-content-center justify-content-md-start">
                    <div class="col-12 col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="icon-user-edit text-primary me-2"></i>
                                    Edit Profile Information
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form enctype="multipart/form-data" method="post" action="{{ route('my_account.update') }}" id="updateProfileForm">
                                    @csrf @method('put')

                                    <!-- Profile Photo -->
                                    <div class="text-center mb-4">
                                        <div class="profile-photo-wrapper mb-3">
                                            <img src="{{ Qs::getUserPhoto($my) }}"
                                                 alt="Profile Photo" 
                                                 class="rounded-circle border border-3 border-white shadow-sm"
                                                 style="width: 120px; height: 120px; object-fit: cover;"
                                                 id="profilePhotoPreview">
                                            <div class="photo-upload-overlay">
                                                <label for="photoUpload" class="btn btn-light btn-sm rounded-circle">
                                                    <i class="icon-camera"></i>
                                                </label>
                                            </div>
                                        </div>
                                        <input accept="image/*" type="file" name="photo" 
                                               id="photoUpload" class="d-none" 
                                               onchange="previewPhoto(this)" {{ Qs::userIsStudent() && Qs::userPhotoIsMissing($my) ? 'required' : '' }}>
                                        <p class="small text-muted mb-0">{{ Qs::userIsStudent() ? 'Student picture is mandatory for facial attendance training' : 'Click camera icon to change photo' }}</p>
                                    </div>

                                    <!-- Read-only Name -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-user text-muted"></i>
                                            </span>
                                            <input disabled class="form-control bg-light border-start-0 ps-0" 
                                                   type="text" value="{{ $my->name }}">
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    @if($my->username)
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-user-check text-muted"></i>
                                            </span>
                                            <input disabled class="form-control bg-light border-start-0 ps-0" 
                                                   type="text" value="{{ $my->username }}">
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-user-plus text-muted"></i>
                                            </span>
                                            <input id="username" name="username" type="text" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Choose a username">
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Email -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-envelope text-muted"></i>
                                            </span>
                                            <input id="email" value="{{ $my->email }}" name="email" 
                                                   type="email" class="form-control border-start-0 ps-0" 
                                                   placeholder="Enter email address">
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="form-label fw-semibold mb-2">Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="icon-phone text-muted"></i>
                                                    </span>
                                                    <input id="phone" value="{{ $my->phone }}" name="phone" 
                                                           type="text" class="form-control border-start-0 ps-0" 
                                                           placeholder="Enter phone number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="form-label fw-semibold mb-2">Telephone</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="icon-phone2 text-muted"></i>
                                                    </span>
                                                    <input id="phone2" value="{{ $my->phone2 }}" name="phone2" 
                                                           type="text" class="form-control border-start-0 ps-0" 
                                                           placeholder="Alternate number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold mb-2">Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="icon-location4 text-muted"></i>
                                            </span>
                                            <input id="address" value="{{ $my->address }}" name="address" 
                                                   type="text" class="form-control border-start-0 ps-0" 
                                                   placeholder="Enter residential address">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4" id="updateProfileBtn">
                                            <i class="icon-paperplane me-2"></i>
                                            Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Navigation Tabs */
.nav-tabs {
    border-bottom: 2px solid rgba(0,0,0,0.05);
    gap: 0.25rem;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
}

.nav-tabs::-webkit-scrollbar {
    height: 3px;
}

.nav-tabs::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.2);
    border-radius: 10px;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    transition: all 0.3s ease;
    white-space: nowrap;
    border-radius: 0.5rem 0.5rem 0 0;
}

.nav-tabs .nav-link:hover {
    color: #007bff;
    background: rgba(0,123,255,0.05);
}

.nav-tabs .nav-link.active {
    color: #007bff;
    background: transparent;
    border-bottom: 3px solid #007bff;
}

/* Profile Photo */
.profile-photo-wrapper {
    position: relative;
    display: inline-block;
}

.photo-upload-overlay {
    position: absolute;
    bottom: 0;
    right: 0;
    opacity: 0;
    transition: all 0.3s ease;
}

.profile-photo-wrapper:hover .photo-upload-overlay {
    opacity: 1;
}

.photo-upload-overlay .btn {
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Password Strength */
.password-strength .progress {
    border-radius: 10px;
    background: #e9ecef;
}

.password-strength .progress-bar {
    transition: width 0.3s ease;
}

/* Form Inputs */
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

/* Button Styling */
.btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220,53,69,0.4);
}

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

/* Alert Box */
.alert-light {
    background: #f8f9fa;
    border-radius: 0.75rem;
}

.requirement i {
    font-size: 0.5rem;
    vertical-align: middle;
}

.requirement.valid i {
    color: #28a745 !important;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1.25rem !important;
    }
    
    .profile-photo-wrapper img {
        width: 100px !important;
        height: 100px !important;
    }
    
    .form-label {
        font-size: 0.875rem;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 1rem;
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
    
    .profile-photo-wrapper img {
        width: 80px !important;
        height: 80px !important;
    }
    
    .photo-upload-overlay .btn {
        width: 32px;
        height: 32px;
    }
    
    .photo-upload-overlay .btn i {
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

.tab-pane.active {
    animation: fadeIn 0.5s ease;
}

/* Loading States */
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

/* Print Styles */
@media print {
    .navbar, .card-header button, .btn {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    window.togglePassword = function(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'icon-eye-blocked';
        } else {
            input.type = 'password';
            icon.className = 'icon-eye';
        }
    };
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordStrength);
        passwordInput.addEventListener('input', checkPasswordMatch);
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }
    
    function checkPasswordStrength() {
        const password = passwordInput.value;
        const progressBar = document.querySelector('.password-strength .progress-bar');
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password)
        };
        
        // Update requirement icons
        Object.keys(requirements).forEach(key => {
            const req = document.querySelector(`.requirement[data-requirement="${key}"]`);
            if (req) {
                const icon = req.querySelector('i');
                if (requirements[key]) {
                    req.classList.add('valid');
                    icon.className = 'icon-checkmark-circle text-success me-2';
                } else {
                    req.classList.remove('valid');
                    icon.className = 'icon-circle text-muted me-2';
                }
            }
        });
        
        // Update progress bar
        const strength = Object.values(requirements).filter(Boolean).length;
        const percentage = (strength / 4) * 100;
        
        if (progressBar) {
            progressBar.style.width = percentage + '%';
            
            if (percentage <= 25) {
                progressBar.className = 'progress-bar bg-danger';
            } else if (percentage <= 50) {
                progressBar.className = 'progress-bar bg-warning';
            } else if (percentage <= 75) {
                progressBar.className = 'progress-bar bg-info';
            } else {
                progressBar.className = 'progress-bar bg-success';
            }
        }
    }
    
    function checkPasswordMatch() {
        const password = passwordInput ? passwordInput.value : '';
        const confirm = confirmInput ? confirmInput.value : '';
        const matchDiv = document.querySelector('.password-match');
        const matchReq = document.querySelector('.requirement[data-requirement="match"]');
        
        if (confirm && matchDiv && matchReq) {
            if (password === confirm) {
                matchDiv.classList.remove('d-none');
                matchReq.classList.add('valid');
                matchReq.querySelector('i').className = 'icon-checkmark-circle text-success me-2';
            } else {
                matchDiv.classList.add('d-none');
                matchReq.classList.remove('valid');
                matchReq.querySelector('i').className = 'icon-circle text-muted me-2';
            }
        }
    }
    
    // Form submission loading states
    const changePassForm = document.getElementById('changePasswordForm');
    if (changePassForm) {
        changePassForm.addEventListener('submit', function() {
            const btn = document.getElementById('changePassBtn');
            btn.classList.add('btn-loading');
            btn.disabled = true;
        });
    }
    
    const updateProfileForm = document.getElementById('updateProfileForm');
    if (updateProfileForm) {
        updateProfileForm.addEventListener('submit', function() {
            const btn = document.getElementById('updateProfileBtn');
            btn.classList.add('btn-loading');
            btn.disabled = true;
        });
    }
});

// Photo preview
window.previewPhoto = function(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('profilePhotoPreview');
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
};
</script>
@endsection