@extends('layouts.master')
@section('page_title', 'Reports')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between"><h6 class="mb-0">Reports</h6><a href="{{ route('admin.reports.create') }}" class="btn btn-primary">Add Report</a></div>
<div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>#</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($records as $row)
<tr><td>{{ $row->id }}</td><td>{{ $row->title }}</td><td>{{ $row->category }}</td><td>{{ $row->status }}</td><td><a class="btn btn-sm btn-info" href="{{ route('admin.reports.edit', $row) }}">Edit</a><form method="POST" action="{{ route('admin.reports.destroy', $row) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete report?')">Delete</button></form></td></tr>
@empty<tr><td colspan="5">No records found.</td></tr>@endforelse
</tbody></table>{{ $records->links() }}</div></div>
@endsection
