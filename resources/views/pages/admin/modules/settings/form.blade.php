<div class="form-group"><label>Key</label><input name="type" class="form-control" value="{{ old('type', $record->type ?? '') }}" required></div>
<div class="form-group"><label>Value</label><textarea name="description" class="form-control" rows="5">{{ old('description', $record->description ?? '') }}</textarea></div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.system-settings.index') }}" class="btn btn-light">Cancel</a>
