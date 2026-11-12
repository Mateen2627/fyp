@extends('layouts.master')
@section('page_title', 'Student Reference Photos')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">Student Reference Photos</h5>
            <small class="text-muted">Add or replace the official photo used to verify facial attendance. You can manage students in your departments only.</small>
        </div>
        <a href="{{ route('academic.progress.index') }}" class="btn btn-light">Department Progress</a>
    </div>
    <div class="card-body">
        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif
        <form method="GET" action="{{ route('academic.reference-photos.index') }}" class="form-inline mb-3">
            <label for="search" class="sr-only">Search students</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" class="form-control mr-2 mb-2" placeholder="Student name or username">
            <button class="btn btn-primary mr-2 mb-2" type="submit">Search</button>
            @if(request()->filled('search'))<a class="btn btn-light mb-2" href="{{ route('academic.reference-photos.index') }}">Clear</a>@endif
        </form>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>Student</th><th>Department / Semester</th><th>Section</th><th>Official Photo</th><th>Upload / Replace</th></tr></thead>
                <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ optional($record->user)->name }}<br><small class="text-muted">{{ optional($record->user)->username }}</small></td>
                        <td>{{ optional(optional($record->my_class)->class_type)->name }} / {{ optional($record->my_class)->name }}</td>
                        <td>{{ optional($record->section)->name ?: '—' }}</td>
                        <td>
                            @if(optional($record->user)->reference_photo_path)
                                <a href="{{ route('academic.reference-photos.show', $record->user_id) }}" target="_blank" rel="noopener">View current photo</a>
                                <span class="badge badge-success">Ready</span>
                            @else
                                <span class="badge badge-warning">Missing</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('academic.reference-photos.update', $record->user_id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="d-flex align-items-center">
                                    <input type="file" name="reference_photo" class="form-control-file mr-2" accept="image/jpeg,image/png" required>
                                    <button type="submit" class="btn btn-sm btn-primary">{{ optional($record->user)->reference_photo_path ? 'Replace' : 'Upload' }}</button>
                                </div>
                                <small class="text-muted">JPG or PNG, up to 5 MB. Use a clear, front-facing photo.</small>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No active students found in your departments.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $records->links() }}
    </div>
</div>
@endsection
