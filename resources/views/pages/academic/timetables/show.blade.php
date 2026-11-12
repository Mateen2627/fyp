@extends('layouts.master')
@section('page_title', 'Class Timetable')
@section('content')
<div class="card"><div class="card-header"><h5>{{ $record->name }}</h5><span>{{ optional($record->my_class)->name }} · {{ optional($record->section)->name ?: 'All sections' }} · {{ $record->year }}</span></div><div class="table-responsive"><table class="table"><thead><tr><th>Day</th><th>Time</th><th>Subject</th>@if($editable)<th>Actions</th>@endif</tr></thead><tbody>
@forelse($lessons as $lesson)<tr><td>{{ $lesson->day ?: $lesson->exam_date }}</td><td>{{ optional($lesson->time_slot)->full }}</td><td>{{ optional($lesson->subject)->name }}</td>@if($editable)<td><form method="post" action="{{ route('tt.delete', $lesson->id) }}" onsubmit="return confirm('Remove this lesson?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Remove</button></form>
<details class="mt-2"><summary>Edit lesson</summary><form class="mt-2" method="post" action="{{ route('tt.update', $lesson->id) }}">@csrf @method('PUT')<input type="hidden" name="ttr_id" value="{{ $record->id }}">
<select name="day" class="form-control mb-2" required>@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)<option {{ $lesson->day === $day ? 'selected' : '' }}>{{ $day }}</option>@endforeach</select>
<select name="subject_id" class="form-control mb-2" required>@foreach($courses as $course)<option value="{{ $course->subject_id }}" {{ $lesson->subject_id == $course->subject_id ? 'selected' : '' }}>{{ $course->title }}</option>@endforeach</select>
<select name="ts_id" class="form-control mb-2" required>@foreach($slots as $slot)<option value="{{ $slot->id }}" {{ $lesson->ts_id == $slot->id ? 'selected' : '' }}>{{ $slot->full }}</option>@endforeach</select><button class="btn btn-sm btn-primary">Save Lesson</button></form></details>
</td>@endif</tr>@empty<tr><td colspan="4">No lessons scheduled.</td></tr>@endforelse</tbody></table></div></div>
@if($editable)
<div class="card"><div class="card-header"><h6>Add Lesson</h6></div><div class="card-body"><form method="post" action="{{ route('tt.store') }}">@csrf<input type="hidden" name="ttr_id" value="{{ $record->id }}">
<div class="form-group"><label>Day</label><select name="day" class="form-control" required>@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)<option>{{ $day }}</option>@endforeach</select></div>
<div class="form-group"><label>Subject</label><select name="subject_id" class="form-control" required><option value="">Select assigned subject</option>@foreach($courses as $course)<option value="{{ $course->subject_id }}">{{ $course->title }}</option>@endforeach</select></div>
<div class="form-group"><label>Time</label><select name="ts_id" class="form-control" required><option value="">Select time slot</option>@foreach($slots as $slot)<option value="{{ $slot->id }}">{{ $slot->full }}</option>@endforeach</select></div><button class="btn btn-primary">Schedule Lesson</button></form></div></div>
<div class="card"><div class="card-header"><h6>Time Slots</h6></div><div class="card-body">
@foreach($slots as $slot)<details class="mb-3"><summary>{{ $slot->full }}</summary><form class="mt-2" method="post" action="{{ route('ts.update', $slot->id) }}">@csrf @method('PUT')<input type="hidden" name="ttr_id" value="{{ $record->id }}">@include('pages.academic.timetables.slot-fields')<button class="btn btn-sm btn-primary">Update Time Slot</button></form><form class="mt-2" method="post" action="{{ route('ts.destroy', $slot->id) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete Empty Slot</button></form></details>@endforeach
<h6>Add Time Slot</h6><form method="post" action="{{ route('ts.store') }}">@csrf<input type="hidden" name="ttr_id" value="{{ $record->id }}">@include('pages.academic.timetables.slot-fields', ['slot' => null])<button class="btn btn-primary">Add Time Slot</button></form></div></div>
@endif
@endsection
