@extends('layouts.master')
@section('page_title', 'Manage Users')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-md-0">
            <i class="icon-users text-primary me-2"></i>
            Manage Users
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-highlight flex-nowrap flex-sm-wrap overflow-auto pb-2 pb-sm-0 mb-4" role="tablist">
            <li class="nav-item me-1">
                <a href="#new-user" class="nav-link active px-3 px-sm-4" data-toggle="tab" role="tab">
                    <i class="icon-user-plus me-1 d-none d-sm-inline"></i>
                    Create New User
                </a>
            </li>
            <li class="nav-item dropdown ms-auto">
                <a href="#" class="nav-link dropdown-toggle px-3 px-sm-4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-users4 me-1"></i>
                    <span class="d-none d-sm-inline">Manage Users</span>
                    <span class="d-sm-none">Users</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                    @foreach($user_types as $ut)
                        <a href="#ut-{{ Qs::hash($ut->id) }}" class="dropdown-item py-2 px-4" data-toggle="tab" role="tab">
                            <i class="icon-user-tie me-2"></i>
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
        <div class="tab-content">
            <!-- Create New User Tab -->
            <div class="tab-pane fade show active" id="new-user" role="tabpanel">
                <div class="row justify-content-center justify-content-md-start">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="icon-user-plus text-primary me-2"></i>
                                    Create New User Account
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form method="post" enctype="multipart/form-data" 
                                      class="wizard-form steps-validation ajax-store" 
                                      action="{{ route('users.store') }}" 
                                      id="createUserForm" data-fouc>
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
                                                <div class="col-12 col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">
                                                            User Type <span class="text-danger">*</span>
                                                        </label>
                                                        <select required class="form-control select-search" 
                                                                name="user_type" id="user_type">
                                                            @foreach($user_types as $ut)
                                                                <option value="{{ Qs::hash($ut->id) }}">
                                                                    {{ $ut->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-5">
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

                                                <div class="col-12 col-md-5">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">
                                                            Address <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="icon-location4 text-muted"></i>
                                                            </span>
                                                            <input value="{{ old('address') }}" required type="text" 
                                                                   name="address" placeholder="Enter address" 
                                                                   class="form-control border-start-0 ps-0">
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
                                                            <input value="{{ old('email') }}" type="email" 
                                                                   name="email" placeholder="user@example.com" 
                                                                   class="form-control border-start-0 ps-0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">Username</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="icon-user-check text-muted"></i>
                                                            </span>
                                                            <input value="{{ old('username') }}" type="text" 
                                                                   name="username" placeholder="Choose username" 
                                                                   class="form-control border-start-0 ps-0">
                                                        </div>
                                                        <small class="text-muted">Leave empty for auto-generated username</small>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">Phone Number</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="icon-phone text-muted"></i>
                                                            </span>
                                                            <input value="{{ old('phone') }}" type="text" 
                                                                   name="phone" placeholder="Enter phone number" 
                                                                   class="form-control border-start-0 ps-0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">Telephone</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="icon-phone2 text-muted"></i>
                                                            </span>
                                                            <input value="{{ old('phone2') }}" type="text" 
                                                                   name="phone2" placeholder="Alternate number" 
                                                                   class="form-control border-start-0 ps-0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">Date of Employment</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="icon-calendar text-muted"></i>
                                                            </span>
                                                            <input autocomplete="off" name="emp_date" 
                                                                   value="{{ old('emp_date') }}" type="text" 
                                                                   class="form-control border-start-0 ps-0 date-pick" 
                                                                   placeholder="Select date">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">Password</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="icon-lock text-muted"></i>
                                                            </span>
                                                            <input id="password" type="password" name="password" 
                                                                   class="form-control border-start-0 ps-0" 
                                                                   placeholder="Enter password">
                                                            <button class="btn btn-light border" type="button" 
                                                                    onclick="togglePassword('password', this)">
                                                                <i class="icon-eye"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">Leave empty for auto-generated password</small>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">
                                                            Gender <span class="text-danger">*</span>
                                                        </label>
                                                        <select class="select form-control" id="gender" 
                                                                name="gender" required data-placeholder="Select gender">
                                                            <option value=""></option>
                                                            <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                                            <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">
                                                            Nationality <span class="text-danger">*</span>
                                                        </label>
                                                        <select required name="nal_id" id="nal_id" 
                                                                class="select-search form-control" 
                                                                data-placeholder="Select nationality">
                                                            <option value=""></option>
                                                            @foreach($nationals as $nal)
                                                                <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} 
                                                                        value="{{ $nal->id }}">{{ $nal->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">
                                                            State <span class="text-danger">*</span>
                                                        </label>
                                                        <select onchange="getLGA(this.value)" required 
                                                                class="select-search form-control" 
                                                                name="state_id" id="state_id" 
                                                                data-placeholder="Select state">
                                                            <option value=""></option>
                                                            @foreach($states as $st)
                                                                <option {{ (old('state_id') == $st->id ? 'selected' : '') }} 
                                                                        value="{{ $st->id }}">{{ $st->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">
                                                            LGA <span class="text-danger">*</span>
                                                        </label>
                                                        <select required class="select-search form-control" 
                                                                name="lga_id" id="lga_id" 
                                                                data-placeholder="Select state first">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">Blood Group</label>
                                                        <select class="select form-control" id="bg_id" 
                                                                name="bg_id" data-placeholder="Select blood group">
                                                            <option value=""></option>
                                                            @foreach($blood_groups as $bg)
                                                                <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} 
                                                                        value="{{ $bg->id }}">{{ $bg->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold d-block">Passport Photograph</label>
                                                        <div class="custom-file-upload">
                                                            <input value="{{ old('photo') }}" accept="image/*" 
                                                                   type="file" name="photo" class="form-input-styled" 
                                                                   id="photoUpload" data-fouc>
                                                            <label for="photoUpload" class="btn btn-light btn-sm mt-2">
                                                                <i class="icon-upload me-1"></i> Choose File
                                                            </label>
                                                        </div>
                                                        <span class="form-text text-muted small">
                                                            <i class="icon-info3 me-1"></i>
                                                            Accepted: jpeg, png. Max size: 2MB
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="reset" class="btn btn-light me-2" id="resetFormBtn">
                                            <i class="icon-reset me-1"></i>
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                            <i class="icon-paperplane me-2"></i>
                                            Create User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Lists Tabs -->
            @foreach($user_types as $ut)
                <div class="tab-pane fade" id="ut-{{Qs::hash($ut->id)}}" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between">
                            <h6 class="fw-bold mb-2 mb-sm-0">
                                <i class="icon-users4 text-primary me-2"></i>
                                {{ $ut->name }}s List
                            </h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="icon-users me-1"></i>
                                Total: {{ $users->where('user_type', $ut->title)->count() }} {{ $ut->name }}s
                            </span>
                        </div>
                        
                        <div class="card-body p-0 p-md-3">
                            @if($users->where('user_type', $ut->title)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover datatable-button-html5-columns mb-0" id="usersTable-{{ $loop->index }}">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 px-md-4" width="50">#</th>
                                            <th class="px-3 px-md-4">Photo</th>
                                            <th class="px-3 px-md-4">Name</th>
                                            <th class="px-3 px-md-4">Username</th>
                                            <th class="px-3 px-md-4">Phone</th>
                                            <th class="px-3 px-md-4">Email</th>
                                            <th class="px-3 px-md-4 text-center" width="120">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users->where('user_type', $ut->title) as $u)
                                        <tr class="user-row">
                                            <td class="px-3 px-md-4 fw-semibold">{{ $loop->iteration }}</td>
                                            <td class="px-3 px-md-4">
                                                <div class="user-avatar">
                                                    <img class="rounded-circle border border-2 border-white shadow-sm" 
                                                         style="height: 40px; width: 40px; object-fit: cover;" 
                                                         src="{{ $u->photo }}" 
                                                         alt="{{ $u->name }}"
                                                         loading="lazy"
                                                         onerror="this.src='{{ asset('global_assets/images/placeholder.jpg') }}'">
                                                </div>
                                            </td>
                                            <td class="px-3 px-md-4">
                                                <span class="fw-semibold">{{ $u->name }}</span>
                                                <small class="text-muted d-block">{{ $ut->name }}</small>
                                            </td>
                                            <td class="px-3 px-md-4">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    <i class="icon-user-check me-1"></i>
                                                    {{ $u->username ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-3 px-md-4">
                                                {{ $u->phone ?: 'N/A' }}
                                            </td>
                                            <td class="px-3 px-md-4">
                                                {{ $u->email ?: 'N/A' }}
                                            </td>
                                            <td class="px-3 px-md-4 text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" 
                                                            type="button" data-toggle="dropdown" 
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <i class="icon-menu9"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-2">
                                                        <a href="{{ route('users.show', Qs::hash($u->id)) }}" 
                                                           class="dropdown-item py-2">
                                                            <i class="icon-eye text-primary me-2"></i>
                                                            View Profile
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="{{ route('users.edit', Qs::hash($u->id)) }}" 
                                                           class="dropdown-item py-2">
                                                            <i class="icon-pencil text-info me-2"></i>
                                                            Edit User
                                                        </a>
                                                        @if(Qs::userIsSuperAdmin())
                                                        <a href="{{ route('users.reset_pass', Qs::hash($u->id)) }}" 
                                                           class="dropdown-item py-2">
                                                            <i class="icon-lock text-warning me-2"></i>
                                                            Reset Password
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a id="{{ Qs::hash($u->id) }}" 
                                                           onclick="confirmDelete(this.id)" 
                                                           href="#" class="dropdown-item text-danger py-2">
                                                            <i class="icon-trash me-2"></i>
                                                            Delete User
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
                            <div class="text-center py-5">
                                <i class="icon-users icon-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No {{ $ut->name }}s found</h6>
                                <p class="small text-muted">Click "Create New User" to add one.</p>
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
    <div id="userToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="icon-checkmark-circle me-2"></i>
                <span id="toastMessage">User created successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Add the rest of the CSS and JavaScript from your original code (it's already well-written) -->
<!-- The CSS and JS sections remain exactly the same as in your working version -->

@endsection