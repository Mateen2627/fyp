<div class="form-section">
    <div class="form-section-title">
        <i class="fas fa-user"></i> User Login Details
    </div>

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
            <select name="user_type" id="user_type" class="form-control-modern" required>
                <option value="">— Select User Type —</option>
                @foreach(['super_admin','admin','teacher','accountant','librarian','student','parent'] as $type)
                    <option value="{{ $type }}" {{ old('user_type', $record->user_type ?? '') === $type ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
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
                    Minimum 6 characters
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
</div>

@if(!isset($record))
<div id="student-create-sections" style="display: none;">
    <div class="form-section">
        <div class="form-section-title">
            <i class="fas fa-user-graduate"></i> Required Student Academic Details
        </div>
        <div class="helper-text" style="margin-bottom: 16px;">
            <i class="fas fa-info-circle"></i> These fields become compulsory when the selected user type is Student.
        </div>

        <div class="form-grid">
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-school"></i> Class <span class="required">*</span>
                </label>
                <select name="my_class_id" id="my_class_id" class="form-control-modern student-required">
                    <option value="">— Select Class —</option>
                    @foreach(($classes ?? collect()) as $class)
                        <option value="{{ $class->id }}" {{ old('my_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-layer-group"></i> Section <span class="required">*</span>
                </label>
                <select name="section_id" id="section_id" class="form-control-modern student-required" data-old-value="{{ old('section_id') }}">
                    <option value="">— Select Class First —</option>
                    @foreach(($classes ?? collect()) as $class)
                        @foreach($class->section as $section)
                            <option value="{{ $section->id }}" data-class-id="{{ $class->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-calendar-alt"></i> Year Admitted <span class="required">*</span>
                </label>
                <select name="year_admitted" class="form-control-modern student-required">
                    <option value="">— Select Year —</option>
                    @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                        <option value="{{ $year }}" {{ old('year_admitted') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-id-badge"></i> Admission Number
                </label>
                <input type="text" name="adm_no" class="form-control-modern" value="{{ old('adm_no') }}" placeholder="Leave blank to use username/id">
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title">
            <i class="fas fa-user-friends"></i> Required Parent Account Details
        </div>
        <div class="helper-text" style="margin-bottom: 16px;">
            <i class="fas fa-info-circle"></i> A parent login will be created automatically and linked to this student.
        </div>

        <div class="form-grid">
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-user"></i> Parent Name <span class="required">*</span>
                </label>
                <input type="text" name="parent_name" class="form-control-modern student-required" value="{{ old('parent_name') }}" placeholder="Enter parent full name">
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-envelope"></i> Parent Email <span class="required">*</span>
                </label>
                <input type="email" name="parent_email" class="form-control-modern student-required" value="{{ old('parent_email') }}" placeholder="parent@example.com">
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-id-card"></i> Parent Username
                </label>
                <input type="text" name="parent_username" class="form-control-modern" value="{{ old('parent_username') }}" placeholder="Optional; email used if blank">
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-lock"></i> Parent Password <span class="required">*</span>
                </label>
                <div class="password-wrapper">
                    <input type="password" name="parent_password" id="parent_password" class="form-control-modern student-required" placeholder="Enter parent password">
                    <button type="button" class="password-toggle" onclick="togglePassword('parent_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-check-circle"></i> Confirm Parent Password <span class="required">*</span>
                </label>
                <div class="password-wrapper">
                    <input type="password" name="parent_password_confirmation" id="parent_password_confirmation" class="form-control-modern student-required" placeholder="Confirm parent password">
                    <button type="button" class="password-toggle" onclick="togglePassword('parent_password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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

    document.addEventListener('DOMContentLoaded', function () {
        const userType = document.getElementById('user_type');
        const studentSections = document.getElementById('student-create-sections');
        const studentRequiredFields = document.querySelectorAll('.student-required');
        const classSelect = document.getElementById('my_class_id');
        const sectionSelect = document.getElementById('section_id');

        function toggleStudentSections() {
            const isStudent = userType && userType.value === 'student';

            if (studentSections) {
                studentSections.style.display = isStudent ? 'block' : 'none';
            }

            studentRequiredFields.forEach(function (field) {
                field.required = isStudent;
            });
        }

        function filterSections() {
            if (!classSelect || !sectionSelect) return;

            const selectedClassId = classSelect.value;
            let hasVisibleSelectedOption = false;

            sectionSelect.querySelectorAll('option').forEach(function (option) {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                const isVisible = option.dataset.classId === selectedClassId;
                option.hidden = !isVisible;
                option.disabled = !isVisible;

                if (isVisible && option.selected) {
                    hasVisibleSelectedOption = true;
                }
            });

            if (!hasVisibleSelectedOption) {
                sectionSelect.value = '';
            }
        }

        if (userType) {
            userType.addEventListener('change', toggleStudentSections);
        }

        if (classSelect) {
            classSelect.addEventListener('change', filterSections);
        }

        filterSections();
        toggleStudentSections();
    });
</script>
