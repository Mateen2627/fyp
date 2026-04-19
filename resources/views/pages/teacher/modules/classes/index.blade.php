@extends('layouts.master')
@section('page_title', 'My Classes & Timetable')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Select Class Timetable Record</h6></div>
    <div class="card-body">
        <form method="get">
            <div class="form-row align-items-end">
                <div class="col-md-8 form-group">
                    <label>Timetable Record</label>
                    <select name="ttr_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($records as $record)
                            <option value="{{ $record->id }}" {{ (string)$selected === (string)$record->id ? 'selected' : '' }}>{{ optional($record->my_class)->name }} - {{ $record->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group"><button class="btn btn-primary">View Timetable</button></div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="mb-0">Timetable</h6></div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead><tr><th>Day/Date</th><th>Slot</th><th>Subject</th></tr></thead>
            <tbody>
            @forelse($timeTables as $tt)
                <tr>
                    <td>{{ $tt->day ?: $tt->exam_date ?: '-' }}</td>
                    <td>{{ optional($tt->time_slot)->full ?: '-' }}</td>
                    <td>{{ optional($tt->subject)->name ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No timetable data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
