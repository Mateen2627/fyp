<div class="form-group"><label>Title</label><input name="title" class="form-control" value="{{ old('title', $record->title ?? '') }}" required></div>
<div class="form-group"><label>Category</label><input name="category" class="form-control" value="{{ old('category', $record->category ?? '') }}"></div>
<div class="form-group"><label>Status</label><select name="status" class="form-control" required>@foreach(['draft','published','archived'] as $status)<option value="{{ $status }}" {{ old('status', $record->status ?? 'draft') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach</select></div>
<div class="form-group"><label>Content</label><textarea name="content" class="form-control" rows="6" required>{{ old('content', $record->content ?? '') }}</textarea></div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.reports.index') }}" class="btn btn-light">Cancel</a>
