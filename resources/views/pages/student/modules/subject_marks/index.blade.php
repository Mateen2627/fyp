@extends('layouts.master')
@section('page_title', 'My Subject Marks')
@section('content')

<style>
    .student-marks-header { background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%); color: #fff; border-radius: 18px; padding: 24px; margin-bottom: 22px; }
    .student-marks-header h4 { color: #fff; margin-bottom: 6px; font-weight: 800; }
    .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 22px; }
    .summary-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
    .summary-card h3 { margin: 0; font-weight: 800; }
    .mark-pill { border-radius: 999px; padding: 7px 12px; font-weight: 700; background: #eef2ff; color: #3730a3; }
</style>

<div class="student-marks-header">
    <h4><i class="fas fa-chart-line"></i> My Mid & Final Subject Marks</h4>
    <div>View the mid and final marks your teachers have entered for each subject.</div>
</div>

<form method="get" action="{{ route('student.subject_marks.index') }}" class="card card-body mb-3">
    <div class="row align-items-end">
        <div class="col-md-4">
            <label>Academic Year</label>
            <input type="text" name="academic_year" value="{{ $academicYear }}" class="form-control" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </div>
</form>

<div class="summary-grid">
    <div class="summary-card"><h3>{{ $summary['subjects'] }}</h3><span>Subjects Marked</span></div>
    <div class="summary-card"><h3 class="text-info">{{ number_format($summary['mid_total'], 2) }}</h3><span>Total Mid Marks</span></div>
    <div class="summary-card"><h3 class="text-primary">{{ number_format($summary['final_total'], 2) }}</h3><span>Total Final Marks</span></div>
    <div class="summary-card"><h3 class="text-success">{{ number_format($summary['overall_total'], 2) }}</h3><span>Overall Total</span></div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Subject Marks for {{ $academicYear }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Class/Section</th>
                <th>Teacher</th>
                <th>Mid Marks</th>
                <th>Final Marks</th>
                <th>Total</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @forelse($marks as $mark)
                <tr>
                    <td><strong>{{ optional($mark->subject)->name }}</strong></td>
                    <td>{{ optional($mark->my_class)->name }} {{ optional($mark->section)->name }}</td>
                    <td>{{ optional($mark->teacher)->name ?: '-' }}</td>
                    <td><span class="mark-pill">{{ $mark->mid_marks !== null ? number_format($mark->mid_marks, 2) : '-' }}</span></td>
                    <td><span class="mark-pill">{{ $mark->final_marks !== null ? number_format($mark->final_marks, 2) : '-' }}</span></td>
                    <td><span class="badge badge-success p-2">{{ number_format($mark->total_marks, 2) }}</span></td>
                    <td>{{ $mark->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No subject marks have been entered for this academic year yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $marks->links() }}</div>
</div>

@endsection
