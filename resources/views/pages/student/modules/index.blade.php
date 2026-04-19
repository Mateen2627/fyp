@extends('layouts.master')
@section('page_title', $title)
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title mb-0">{{ $title }}</h6>
        <div class="header-elements">
            <a href="{{ route($routeBase.'.create') }}" class="btn btn-primary btn-sm">Add New</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Details</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->id }}</td>
                        <td><pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($record->toArray(), JSON_PRETTY_PRINT) }}</pre></td>
                        <td class="text-right">
                            <a href="{{ route($routeBase.'.show', $record->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route($routeBase.'.edit', $record->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route($routeBase.'.destroy', $record->id) }}" method="post" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center p-4">No records found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>
@endsection
