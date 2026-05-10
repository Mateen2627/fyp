@extends('layouts.master')
@section('page_title', 'Manage Users')
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
    
    .card-header-modern {
        background: white;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    @media (min-width: 768px) {
        .card-header-modern {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    
    .card-title-modern {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-title-modern i {
        color: var(--primary);
        font-size: 20px;
    }
    
    .table-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .table-filter-bar .form-control {
        max-width: 320px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 13px;
    }

    /* Tabs */
    .nav-tabs-modern {
        border-bottom: 1px solid var(--border);
        padding: 0 24px;
        background: white;
    }
    
    .nav-tabs-modern .nav-link {
        padding: 14px 20px;
        border: none;
        color: var(--gray);
        font-weight: 500;
        font-size: 14px;
        position: relative;
        background: transparent;
    }
    
    .nav-tabs-modern .nav-link i {
        margin-right: 8px;
        font-size: 14px;
    }
    
    .nav-tabs-modern .nav-link.active {
        color: var(--primary);
        background: transparent;
    }
    
    .nav-tabs-modern .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary);
        border-radius: 2px;
    }
    
    .nav-tabs-modern .nav-link:hover:not(.active) {
        color: var(--dark);
        background: var(--light);
        border-radius: 8px 8px 0 0;
    }
    
    /* Section Card */
    .section-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 24px;
    }
    
    .section-header {
        padding: 16px 20px;
        background: var(--light);
        border-bottom: 1px solid var(--border);
    }
    
    .section-header h6 {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-header h6 i {
        color: var(--primary);
    }
    
    .section-body {
        padding: 24px;
    }
    
    /* Form Elements */
    .form-label-modern {
        font-size: 12px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 6px;
        display: block;
    }
    
    .form-label-modern .text-danger {
        color: var(--danger);
    }
    
    .input-group-modern {
        display: flex;
        align-items: stretch;
    }
    
    .input-group-text-modern {
        background: var(--light);
        border: 1px solid var(--border);
        border-right: none;
        border-radius: 12px 0 0 12px;
        padding: 0 14px;
        display: flex;
        align-items: center;
        color: var(--gray);
    }
    
    .form-control-modern {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .form-control-modern:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .input-group-modern .form-control-modern {
        border-radius: 0 12px 12px 0;
        border-left: none;
    }
    
    /* Buttons */
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        transition: all 0.2s;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.3);
    }
    
    .btn-light-modern {
        background: var(--light);
        border: 1px solid var(--border);
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        color: var(--gray);
        transition: all 0.2s;
    }
    
    .btn-light-modern:hover {
        background: var(--border);
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
    
    /* Badges */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #f1f5f9;
        color: var(--gray);
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
    
    .empty-state h6 {
        font-size: 16px;
        color: var(--gray);
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 13px;
        color: #94a3b8;
    }
    
    /* User Avatar */
    .user-avatar img {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .section-body {
            padding: 16px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .user-avatar img {
            width: 32px;
            height: 32px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <h5 class="card-title-modern">
            <i class="fas fa-users"></i>
            Manage Users
        </h5>
        <div>
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-0">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs-modern nav-tabs flex-nowrap flex-sm-wrap overflow-auto" role="tablist">
            <li class="nav-item">
                <a href="#new-user" class="nav-link active" data-toggle="tab" role="tab">
                    <i class="fas fa-user-plus"></i>
                    <span class="d-none d-sm-inline">Create New User</span>
                    <span class="d-sm-none">Create</span>
                </a>
            </li>
            <li class="nav-item dropdown ms-auto">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">
                    <i class="fas fa-users"></i>
                    <span class="d-none d-sm-inline">Manage Users</span>
                    <span class="d-sm-none">Users</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2">
                    @foreach($user_types as $ut)
                        <a href="#ut-{{ Qs::hash($ut->id) }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                            <i class="fas fa-user-tie me-2"></i>
                            {{ $ut->name }}s
                            <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">
                                {{ $users->where('user_type', $ut->title)->count() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content p-4">
            <!-- Create New User Tab -->
            <div class="tab-pane fade show active" id="new-user" role="tabpanel">
                <form method="post" enctype="multipart/form-data" 
                      class="ajax-store" action="{{ route('users.store') }}" id="createUserForm">
                    @csrf
                    
                    <!-- Personal Data Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <h6>
                                <i class="fas fa-user-edit"></i>
                                Personal Information
                            </h6>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label-modern">User Type <span class="text-danger">*</span></label>
                                        <select required class="form-control-modern select-search" name="user_type" id="user_type">
                                            @foreach($user_types as $ut)
                                                <option value="{{ Qs::hash($ut->id) }}">{{ $ut->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label class="form-label-modern">Full Name <span class="text-danger">*</span></label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-user"></i></span>
                                            <input value="{{ old('name') }}" required type="text" 
                                                   name="name" placeholder="Enter full name" 
                                                   class="form-control-modern">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label class="form-label-modern">Address <span class="text-danger">*</span></label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-map-marker-alt"></i></span>
                                            <input value="{{ old('address') }}" required type="text" 
                                                   name="address" placeholder="Enter address" 
                                                   class="form-control-modern">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Email Address</label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-envelope"></i></span>
                                            <input value="{{ old('email') }}" type="email" 
                                                   name="email" placeholder="user@example.com" 
                                                   class="form-control-modern">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Username</label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-user-check"></i></span>
                                            <input value="{{ old('username') }}" type="text" 
                                                   name="username" placeholder="Choose username" 
                                                   class="form-control-modern">
                                        </div>
                                        <small class="text-muted">Leave empty for auto-generated</small>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Phone Number</label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-phone"></i></span>
                                            <input value="{{ old('phone') }}" type="text" 
                                                   name="phone" placeholder="Enter phone number" 
                                                   class="form-control-modern">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Telephone</label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-phone-alt"></i></span>
                                            <input value="{{ old('phone2') }}" type="text" 
                                                   name="phone2" placeholder="Alternate number" 
                                                   class="form-control-modern">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Date of Employment</label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-calendar"></i></span>
                                            <input autocomplete="off" name="emp_date" 
                                                   value="{{ old('emp_date') }}" type="text" 
                                                   class="form-control-modern date-pick" 
                                                   placeholder="Select date">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Password</label>
                                        <div class="input-group-modern">
                                            <span class="input-group-text-modern"><i class="fas fa-lock"></i></span>
                                            <input id="password" type="password" name="password" 
                                                   class="form-control-modern" placeholder="Enter password">
                                            <button class="btn-light-modern" type="button" 
                                                    style="border-radius: 0 12px 12px 0; border-left: none;"
                                                    onclick="togglePassword('password', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Leave empty for auto-generated</small>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Gender <span class="text-danger">*</span></label>
                                        <select class="form-control-modern" name="gender" required>
                                            <option value="">Select gender</option>
                                            <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                            <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label-modern">Nationality <span class="text-danger">*</span></label>
                                        <select required name="nal_id" id="nal_id" class="form-control-modern select-search">
                                            <option value="">Select nationality</option>
                                            @foreach($nationals as $nal)
                                                <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} 
                                                        value="{{ $nal->id }}">{{ $nal->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-modern">State <span class="text-danger">*</span></label>
                                        <select onchange="getLGA(this.value)" required class="form-control-modern select-search" 
                                                name="state_id" id="state_id">
                                            <option value="">Select state</option>
                                            @foreach($states as $st)
                                                <option {{ (old('state_id') == $st->id ? 'selected' : '') }} 
                                                        value="{{ $st->id }}">{{ $st->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-modern">LGA <span class="text-danger">*</span></label>
                                        <select required class="form-control-modern select-search" name="lga_id" id="lga_id">
                                            <option value="">Select state first</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-modern">Blood Group</label>
                                        <select class="form-control-modern select" name="bg_id" id="bg_id">
                                            <option value="">Select blood group</option>
                                            @foreach($blood_groups as $bg)
                                                <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} 
                                                        value="{{ $bg->id }}">{{ $bg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label-modern">Passport Photograph</label>
                                        <input accept="image/*" type="file" name="photo" class="form-control-modern" id="photoUpload">
                                        <small class="text-muted">Accepted: jpeg, png. Max size: 2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn-light-modern" id="resetFormBtn">
                            <i class="fas fa-undo-alt me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn-primary-modern" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i> Create User
                        </button>
                    </div>
                </form>
            </div>

            <!-- User Lists Tabs -->
            @foreach($user_types as $ut)
                <div class="tab-pane fade" id="ut-{{Qs::hash($ut->id)}}" role="tabpanel">
                    <div class="section-card">
                        <div class="section-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                            <h6 class="mb-2 mb-sm-0">
                                <i class="fas fa-users"></i>
                                {{ $ut->name }}s List
                            </h6>
                            <span class="badge-modern">
                                <i class="fas fa-user-check me-1"></i>
                                Total: {{ $users->where('user_type', $ut->title)->count() }} {{ $ut->name }}s
                            </span>
                        </div>
                        
                        <div class="section-body p-0">
                            @if($users->where('user_type', $ut->title)->count() > 0)
                            <div class="table-filter-bar">
                                <input type="search" class="form-control support-user-search" data-table-target="support-users-{{ Qs::hash($ut->id) }}" placeholder="Search {{ $ut->name }}s by name, username, phone, or email">
                            </div>
                            <div class="table-responsive">
                                <table class="table-modern mb-0" id="support-users-{{ Qs::hash($ut->id) }}">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th class="text-center" width="100">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users->where('user_type', $ut->title) as $u)
                                        <tr data-search="{{ strtolower($u->name.' '.$u->username.' '.$u->phone.' '.$u->email.' '.$ut->name) }}">
                                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="user-avatar">
                                                    <img class="rounded-circle" 
                                                         src="{{ Qs::getUserPhoto($u) }}"
                                                         alt="{{ $u->name }}"
                                                         onerror="this.src='{{ asset('global_assets/images/placeholder.jpg') }}'">
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $u->name }}</span>
                                                <small class="text-muted d-block">{{ $ut->name }}</small>
                                            </td>
                                            <td>
                                                <span class="badge-modern">
                                                    <i class="fas fa-user-check me-1"></i>
                                                    {{ $u->username ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $u->phone ?: 'N/A' }}</td>
                                            <td>{{ $u->email ?: 'N/A' }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn-light-modern btn-sm dropdown-toggle" 
                                                            style="padding: 6px 12px;"
                                                            type="button" data-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                                        <a href="{{ route('users.show', Qs::hash($u->id)) }}" 
                                                           class="dropdown-item py-2">
                                                            <i class="fas fa-eye text-primary me-2"></i> View Profile
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="{{ route('users.edit', Qs::hash($u->id)) }}" 
                                                           class="dropdown-item py-2">
                                                            <i class="fas fa-edit text-info me-2"></i> Edit User
                                                        </a>
                                                        @if(Qs::userIsSuperAdmin())
                                                        <a href="{{ route('users.reset_pass', Qs::hash($u->id)) }}" 
                                                           class="dropdown-item py-2">
                                                            <i class="fas fa-key text-warning me-2"></i> Reset Password
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a id="{{ Qs::hash($u->id) }}" 
                                                           onclick="confirmDelete(this.id)" 
                                                           href="#" class="dropdown-item text-danger py-2">
                                                            <i class="fas fa-trash-alt me-2"></i> Delete User
                                                        </a>
                                                        <form method="post" id="item-delete-{{ Qs::hash($u->id) }}" 
                                                              action="{{ route('users.destroy', Qs::hash($u->id)) }}" 
                                                              class="hidden">@csrf @method('delete')</form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <h6>No {{ $ut->name }}s found</h6>
                                <p>Click "Create New User" to add one.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="userToast" class="toast align-items-center text-white border-0" role="alert" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage">User created successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.support-user-search').forEach(function (input) {
            input.addEventListener('input', function () {
                const table = document.getElementById(this.dataset.tableTarget);
                const value = this.value.toLowerCase();

                if (!table) return;

                table.querySelectorAll('tbody tr').forEach(function (row) {
                    row.style.display = !value || (row.dataset.search || row.textContent.toLowerCase()).includes(value) ? '' : 'none';
                });
            });
        });
    });
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    function getLGA(stateId) {
        if (stateId) {
            $.ajax({
                url: '{{ route('get_lga') }}',
                type: 'GET',
                data: { state_id: stateId },
                success: function(data) {
                    $('#lga_id').empty().append('<option value="">Select LGA</option>');
                    $.each(data, function(key, value) {
                        $('#lga_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('#lga_id').select2({ dropdownAutoWidth: true, width: '100%' });
                }
            });
        }
    }
</script>

@endsection