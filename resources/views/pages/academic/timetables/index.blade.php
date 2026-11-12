@extends('layouts.master')
@section('page_title', 'Timetables')
@section('content')
<div class="card"><div class="card-header"><h5>Timetables</h5></div><div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Semester</th><th>Section</th><th>Session</th><th>Actions</th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->name }}</td><td>{{ optional($record->my_class)->name }}</td><td>{{ optional($record->section)->name ?: 'All sections' }}</td><td>{{ $record->year }}</td><td><a class="btn btn-sm btn-light" href="{{ route('ttr.show', $record->id) }}">View</a>
@if(\App\Services\LmsAccess::departmentIds('coordinator')->contains(optional($record->my_class)->class_type_id))
<a class="btn btn-sm btn-primary" href="{{ route('ttr.manage', $record->id) }}">Manage Lessons</a> <a class="btn btn-sm btn-light" href="{{ route('ttr.edit', $record->id) }}">Edit Details</a>
<form class="d-inline" method="post" action="{{ route('ttr.destroy', $record->id) }}" onsubmit="return confirm('Delete this timetable and its lessons?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
@endif</td></tr>@empty<tr><td colspan="5">No timetables available.</td></tr>@endforelse</tbody></table></div></div>
@if($classes->isNotEmpty())<div class="card"><div class="card-header"><h5>Create Timetable</h5></div><div class="card-body"><form method="post" action="{{ route('ttr.store') }}">@csrf @include('pages.academic.timetables.fields', ['record' => null])<button class="btn btn-primary">Create Timetable</button></form></div></div>@endif
@endsection
