@extends('layouts.master')
@section('page_title', 'Courses')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Courses Assigned to Your Class</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Course</th><th>Code</th><th>Teacher</th><th>Notes</th></tr></thead>
            <tbody>
            @forelse($courses as $item)
                <tr>
                    <td>{{ optional($item->course)->title }}</td>
                    <td>{{ optional($item->course)->code ?: '-' }}</td>
                    <td>{{ optional($item->teacher)->name ?: 'Not assigned' }}</td>
                    <td>{{ $item->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No courses are assigned for your class yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Class Timetable {{ $ttr ? '(' . $ttr->name . ')' : '' }}</h6></div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead><tr><th>Day/Date</th><th>Time Slot</th><th>Subject</th></tr></thead>
            <tbody>
            @forelse($timeTables as $tt)
                <tr>
                    <td>{{ $tt->day ?: $tt->exam_date ?: '-' }}</td>
                    <td>{{ optional($tt->time_slot)->full ?: ($tt->timestamp_from.' - '.$tt->timestamp_to) }}</td>
                    <td>{{ optional($tt->subject)->name ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No timetable records found for your class.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
