<div class="form-grid">
    <!-- Full Name -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-user"></i> Full Name <span class="required">*</span>
        </label>
        <input type="text" name="name" class="form-control-modern" value="{{ old('name', $record->name ?? '') }}" placeholder="Enter full name" required>
    </div>

    <!-- Email Address -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
        </label>
        <input type="email" name="email" class="form-control-modern" value="{{ old('email', $record->email ?? '') }}" placeholder="user@example.com" required>
    </div>

    <!-- Username -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-id-card"></i> Username
        </label>
        <input type="text" name="username" class="form-control-modern" value="{{ old('username', $record->username ?? '') }}" placeholder="Choose a username">
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> Leave empty for auto-generated username
        </div>
    </div>

    <!-- User Type -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-user-tag"></i> User Type <span class="required">*</span>
        </label>
        <select name="user_type" class="form-control-modern" required>
            <option value="">— Select User Type —</option>
            @foreach(['super_admin','admin','teacher','accountant','librarian','student','parent'] as $type)
                <option value="{{ $type }}" {{ old('user_type', $record->user_type ?? '') === $type ? 'selected' : '' }}>
                    @php
                        $typeIcons = [
                            'super_admin' => 'fa-crown',
                            'admin' => 'fa-shield-alt',
                            'teacher' => 'fa-chalkboard-user',
                            'accountant' => 'fa-calculator',
                            'librarian' => 'fa-book',
                            'student' => 'fa-user-graduate',
                            'parent' => 'fa-heart'
                        ];
                        $icon = $typeIcons[$type] ?? 'fa-user';
                    @endphp
                    <i class="fas {{ $icon }}"></i> {{ ucfirst(str_replace('_', ' ', $type)) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Password -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-lock"></i> Password <span class="required">{{ isset($record) ? '' : '*' }}</span>
        </label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" class="form-control-modern" placeholder="{{ isset($record) ? 'Leave empty to keep current' : 'Enter password' }}" {{ isset($record) ? '' : 'required' }}>
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="helper-text">
            <i class="fas fa-info-circle"></i> 
            @if(isset($record))
                Leave empty to keep current password
            @else
                Minimum 8 characters with letters and numbers
            @endif
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="form-group-modern">
        <label class="form-label-modern">
            <i class="fas fa-check-circle"></i> Confirm Password <span class="required">{{ isset($record) ? '' : '*' }}</span>
        </label>
        <div class="password-wrapper">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control-modern" placeholder="Confirm password" {{ isset($record) ? '' : 'required' }}>
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* Password Wrapper */
    .password-wrapper {
        position: relative;
    }
    
    .password-wrapper input {
        padding-right: 45px;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        transition: color 0.2s;
    }
    
    .password-toggle:hover {
        color: #4f46e5;
    }
    
    /* Select options styling */
    select.form-control-modern option {
        padding: 8px;
    }
    
    select.form-control-modern option i {
        margin-right: 8px;
    }
</style>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const toggle = field.nextElementSibling.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            toggle.classList.remove('fa-eye');
            toggle.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            toggle.classList.remove('fa-eye-slash');
            toggle.classList.add('fa-eye');
        }
    }
</script>