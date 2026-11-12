@extends('layouts.master')
@section('page_title', 'Department Teaching Assignments')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Department Teaching Assignments</h5>
        <a class="btn btn-primary" href="{{ route('hod.assign-teachers.create') }}">Assign Teacher</a>
    </div>
    <div class="card-body"><p class="text-muted mb-0">Assign subjects to teachers within your departments. Choose a section or all sections of a semester.</p></div>
    <div class="table-responsive"><table class="table">
        <thead><tr><th>Semester</th><th>Section</th><th>Subject</th><th>Teacher</th><th>Actions</th></tr></thead>
        <tbody>@forelse($records as $row)
            <tr><td>{{ optional($row->my_class)->name }}</td><td>{{ optional($row->section)->name ?: 'All sections' }}</td><td>{{ optional($row->course)->title }}</td><td>{{ optional($row->teacher)->name }}</td><td>
                <a href="{{ route('hod.assign-teachers.edit', $row) }}" class="btn btn-sm btn-light">Edit</a>
                <form class="d-inline" method="post" action="{{ route('hod.assign-teachers.destroy', $row) }}" onsubmit="return confirm('Revoke this teaching assignment?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Revoke</button></form>
            </td></tr>
        @empty<tr><td colspan="5">No teaching assignments yet.</td></tr>@endforelse</tbody>
    </table></div><div class="card-footer">{{ $records->links() }}</div>
</div>
@endsection
