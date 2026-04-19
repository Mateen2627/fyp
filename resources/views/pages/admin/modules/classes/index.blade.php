@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h6 class="mb-0">Manage Classes</h6>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Add Class</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>ID</th><th>Class Name</th><th>Students</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($records as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->student_record_count }}</td>
                    <td>
                        <a href="{{ route('admin.classes.show', $row) }}" class="btn btn-sm btn-secondary">View</a>
                        <a href="{{ route('admin.classes.edit', $row) }}" class="btn btn-sm btn-info">Edit</a>
                        <form method="POST" action="{{ route('admin.classes.destroy', $row) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete this class?')">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No classes found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>
@endsection
