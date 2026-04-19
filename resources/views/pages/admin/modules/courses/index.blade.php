@extends('layouts.master')
@section('page_title', 'Manage Courses')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between"><h6 class="mb-0">Manage Courses</h6><a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Add Course</a></div>
<div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>#</th><th>Title</th><th>Code</th><th>Class ID</th><th>Actions</th></tr></thead><tbody>
@forelse($records as $row)
<tr><td>{{ $row->id }}</td><td>{{ $row->title }}</td><td>{{ $row->code }}</td><td>{{ $row->my_class_id }}</td><td><a href="{{ route('admin.courses.edit', $row) }}" class="btn btn-sm btn-info">Edit</a><form method="POST" action="{{ route('admin.courses.destroy', $row) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</button></form></td></tr>
@empty<tr><td colspan="5">No records found.</td></tr>@endforelse
</tbody></table>{{ $records->links() }}</div></div>
@endsection
