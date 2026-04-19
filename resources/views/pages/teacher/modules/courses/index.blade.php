@extends('layouts.master')
@section('page_title', 'My Courses')
@section('content')
<div class="card">
    <div class="card-header"><h6 class="mb-0">Courses Assigned to Me</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Course</th><th>Class</th><th>Notes</th></tr></thead>
            <tbody>
            @forelse($courses as $item)
                <tr>
                    <td>{{ optional($item->course)->title ?: '-' }}</td>
                    <td>{{ optional($item->my_class)->name ?: '-' }}</td>
                    <td>{{ $item->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No assigned courses.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
