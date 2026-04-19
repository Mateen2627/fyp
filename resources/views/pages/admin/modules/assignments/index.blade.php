@extends('layouts.master')
@section('page_title', 'Assign Teachers')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between"><h6 class="mb-0">Assign Teachers</h6><a href="{{ route('admin.assign-teachers.create') }}" class="btn btn-primary">New Assignment</a></div>
<div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>#</th><th>Course</th><th>Teacher</th><th>Notes</th><th>Actions</th></tr></thead><tbody>
@forelse($records as $row)
<tr><td>{{ $row->id }}</td><td>{{ optional($row->course)->title }}</td><td>{{ optional($row->teacher)->name }}</td><td>{{ $row->notes }}</td><td><a href="{{ route('admin.assign-teachers.edit', $row) }}" class="btn btn-sm btn-info">Edit</a><form method="POST" action="{{ route('admin.assign-teachers.destroy', $row) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete assignment?')">Delete</button></form></td></tr>
@empty<tr><td colspan="5">No records found.</td></tr>@endforelse
</tbody></table>{{ $records->links() }}</div></div>
@endsection
