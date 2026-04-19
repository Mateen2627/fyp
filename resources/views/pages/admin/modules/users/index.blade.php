@extends('layouts.master')
@section('page_title', 'Manage Users')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Manage Users</h6>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Type</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($records as $row)
                <tr>
                    <td>{{ $row->id }}</td><td>{{ $row->name }}</td><td>{{ $row->email }}</td><td>{{ $row->user_type }}</td>
                    <td>
                        <a class="btn btn-sm btn-info" href="{{ route('admin.users.edit', $row) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $row) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No records found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $records->links() }}
    </div>
</div>
@endsection
