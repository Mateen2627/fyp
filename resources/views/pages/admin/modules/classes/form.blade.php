@php($selected = collect(old('student_record_ids', isset($record) ? $record->student_record->pluck('id')->all() : []))->map(fn($v) => (int)$v)->all())
<div class="form-group">
    <label>Class Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $record->name ?? '') }}" required>
</div>
<div class="form-group">
    <label>Class Type</label>
    <select name="class_type_id" class="form-control">
        <option value="">Select Type</option>
        @foreach($classTypes as $type)
            <option value="{{ $type->id }}" {{ (string)old('class_type_id', $record->class_type_id ?? '') === (string)$type->id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Assign Students</label>
    <select name="student_record_ids[]" class="form-control" multiple size="10">
        @foreach($studentRecords as $sr)
            <option value="{{ $sr->id }}" {{ in_array($sr->id, $selected) ? 'selected' : '' }}>
                #{{ $sr->id }} - {{ optional($sr->user)->name ?: 'Student '.$sr->user_id }} (Current Class ID: {{ $sr->my_class_id }})
            </option>
        @endforeach
    </select>
    <small class="text-muted">Hold Ctrl/Cmd to select multiple students.</small>
</div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.classes.index') }}" class="btn btn-light">Cancel</a>
