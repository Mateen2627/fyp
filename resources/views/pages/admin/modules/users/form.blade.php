<div class="form-group"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $record->name ?? '') }}" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $record->email ?? '') }}" required></div>
<div class="form-group"><label>Username</label><input name="username" class="form-control" value="{{ old('username', $record->username ?? '') }}"></div>
<div class="form-group"><label>User Type</label>
    <select name="user_type" class="form-control" required>
        @foreach(['super_admin','admin','teacher','accountant','librarian','student','parent'] as $type)
            <option value="{{ $type }}" {{ old('user_type', $record->user_type ?? '') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
        @endforeach
    </select>
</div>
<div class="form-group"><label>Password {{ isset($record) ? '(optional)' : '' }}</label><input type="password" name="password" class="form-control" {{ isset($record) ? '' : 'required' }}></div>
<div class="form-group"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control" {{ isset($record) ? '' : 'required' }}></div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
