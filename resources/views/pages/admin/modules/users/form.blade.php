@php($student = $record ? $record->student_record : null)
<div class="form-row">
<div class="col-md-6 form-group"><label for="user-name">Name</label><input id="user-name" name="name" class="form-control" required maxlength="200" value="{{ old('name', optional($record)->name) }}"></div>
<div class="col-md-6 form-group"><label for="user-login">Username</label><input id="user-login" name="username" class="form-control" required maxlength="100" value="{{ old('username', optional($record)->username) }}"></div>
<div class="col-md-6 form-group"><label>Email (optional)</label><input type="email" name="email" class="form-control" maxlength="100" value="{{ old('email', optional($record)->email) }}"></div>
<div class="col-md-6 form-group"><label for="user-type">Account type</label><select id="user-type" name="user_type" class="form-control" required>
@foreach(Auth::user()->user_type === 'super_admin' ? ['student','teacher','admin','super_admin','accountant','librarian','parent'] : ['student','teacher','admin','accountant','librarian','parent'] as $type)
<option value="{{ $type }}" {{ old('user_type', optional($record)->user_type ?: 'student') === $type ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $type)) }}</option>@endforeach
</select><small>HOD and coordinator responsibilities are additional department roles; these staff keep teacher accounts.</small></div>
<div class="col-md-6 form-group"><label>Password</label><input name="password" type="password" class="form-control" minlength="2" autocomplete="new-password"><small>{{ $record ? 'Leave empty to retain the current password.' : 'Leave empty to use the demo password cj.' }}</small></div>
<div class="col-md-6 form-group"><label>Confirm password</label><input name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
</div>
<fieldset id="student-fields"><legend class="h6">Student Placement</legend>
<div class="form-group">
    <label for="reference-photo">Official Reference Photo for Facial Attendance</label>
    <input id="reference-photo" name="reference_photo" type="file" accept="image/jpeg,image/png" class="form-control-file">
    <small class="form-text text-muted">Upload a clear, front-facing JPEG or PNG, up to 5 MB and 4096 by 4096 pixels. Only administrators can change this photo. Leave empty to keep the current reference.</small>
    @if($record && $record->reference_photo_path)
        <a href="{{ route('admin.users.reference_photo', $record) }}" target="_blank" rel="noopener">View Current Official Photo</a>
    @else
        <span class="text-warning d-block">No official reference photo uploaded yet.</span>
    @endif
</div>
<div class="form-row"><div class="col-md-6 form-group"><label for="student-class">Semester</label><select id="student-class" name="my_class_id" class="form-control" data-required><option value="">Select semester</option>@foreach($classes as $class)<option value="{{ $class->id }}" {{ old('my_class_id', optional($student)->my_class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>@endforeach</select></div>
<div class="col-md-6 form-group"><label for="student-section">Section</label><select id="student-section" name="section_id" class="form-control" data-required><option value="">Select section</option>@foreach($classes as $class) @foreach($class->section->where('active', 1) as $section)<option data-class="{{ $class->id }}" value="{{ $section->id }}" {{ old('section_id', optional($student)->section_id) == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>@endforeach @endforeach</select></div>
<div class="col-md-4 form-group"><label>Admission number</label><input name="adm_no" class="form-control" maxlength="30" data-required value="{{ old('adm_no', optional($student)->adm_no) }}"></div>
<div class="col-md-4 form-group"><label>Session</label><input name="session" class="form-control" placeholder="2023-2027" data-required value="{{ old('session', optional($student)->session ?: Qs::getCurrentSession()) }}"></div>
<div class="col-md-4 form-group"><label>Year admitted</label><input name="year_admitted" type="number" min="2000" max="{{ date('Y') }}" class="form-control" data-required value="{{ old('year_admitted', optional($student)->year_admitted ?: 2023) }}"></div></div>
</fieldset>
<fieldset id="teacher-fields"><legend class="h6">Teacher Departments</legend><div class="form-group"><label>Departments</label><select name="department_ids[]" multiple class="form-control" size="7" data-required>
@foreach($departments as $department)<option value="{{ $department->id }}" {{ in_array($department->id, old('department_ids', $record ? $record->departmentRoles->pluck('department_id')->all() : [])) ? 'selected' : '' }}>{{ $department->name }}</option>@endforeach
</select><small>Hold Ctrl/Command to choose multiple departments. Their HODs can assign this teacher to subjects.</small></div></fieldset>
<script>document.addEventListener('DOMContentLoaded',function(){const type=document.getElementById('user-type'),c=document.getElementById('student-class'),s=document.getElementById('student-section');function update(){['student','teacher'].forEach(role=>{const f=document.getElementById(role+'-fields');f.hidden=type.value!==role;f.disabled=f.hidden;f.querySelectorAll('[data-required]').forEach(input=>input.required=!f.hidden);});}function sections(){Array.from(s.options).forEach(o=>{o.disabled=!!o.value&&o.dataset.class!==c.value;o.hidden=o.disabled;});if(s.selectedOptions[0]?.disabled)s.value='';}type.addEventListener('change',update);c.addEventListener('change',sections);update();sections();});</script>
