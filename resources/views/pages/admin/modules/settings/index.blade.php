@extends('layouts.master')
@section('page_title', 'System Settings')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between"><h6 class="mb-0">System Settings</h6><a href="{{ route('admin.system-settings.create') }}" class="btn btn-primary">Add Setting</a></div>
<div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>#</th><th>Key</th><th>Value</th><th>Actions</th></tr></thead><tbody>
@forelse($records as $row)
<tr><td>{{ $row->id }}</td><td>{{ $row->type }}</td><td>{{ Str::limit($row->description, 80) }}</td><td><a class="btn btn-sm btn-info" href="{{ route('admin.system-settings.edit', $row) }}">Edit</a><form method="POST" action="{{ route('admin.system-settings.destroy', $row) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete setting?')">Delete</button></form></td></tr>
@empty<tr><td colspan="4">No records found.</td></tr>@endforelse
</tbody></table>{{ $records->links() }}</div></div>
@endsection
