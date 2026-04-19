<div class="form-group"><label>Title</label><input name="title" class="form-control" value="{{ old('title', $record->title ?? '') }}" required></div>
<div class="form-group"><label>Code</label><input name="code" class="form-control" value="{{ old('code', $record->code ?? '') }}"></div>
<div class="form-group"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $record->description ?? '') }}</textarea></div>
<div class="form-group">
    <label>Class</label>
    <select name="my_class_id" class="form-control">
        <option value="">Not linked to any class</option>
        @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ (string) old('my_class_id', $record->my_class_id ?? '') === (string) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
        @endforeach
    </select>
</div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.courses.index') }}" class="btn btn-light">Cancel</a>
