@extends('layouts.master')
@section('page_title', 'Subject Mid & Final Marks')
@section('content')

<style>
    .marks-hero { background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color: #fff; border-radius: 18px; padding: 24px; margin-bottom: 22px; box-shadow: 0 18px 45px rgba(37, 99, 235, 0.18); }
    .marks-hero h4 { color: #fff; margin-bottom: 6px; font-weight: 800; }
    .marks-filter { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; }
    .student-cell { display: flex; align-items: center; gap: 12px; min-width: 220px; }
    .student-cell img { width: 46px; height: 46px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
    .marks-input { max-width: 110px; text-align: center; }
    .total-badge { font-size: 13px; padding: 8px 12px; border-radius: 999px; background: #eef2ff; color: #3730a3; font-weight: 700; }
</style>

<div class="marks-hero">
    <h4><i class="fas fa-clipboard-check"></i> Subject Mid & Final Marks</h4>
    <div>Choose one of your assigned subjects, then enter mid and final marks for each student in that class/section.</div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Select Subject</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <form method="get" action="{{ route('teacher.subject_marks.index') }}" class="marks-filter">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label>Subject</label>
                    <select name="subject_id" class="form-control" required>
                        @forelse($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ optional($selectedSubject)->id === $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} - {{ optional($subject->my_class)->name }}
                            </option>
                        @empty
                            <option value="">No subjects assigned</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Section</label>
                    <select name="section_id" class="form-control">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ (int) $selectedSectionId === (int) $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Academic Year</label>
                    <input type="text" name="academic_year" value="{{ $academicYear }}" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Load Students</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selectedSubject)
    <div class="card">
        <div class="card-header header-elements-inline">
            <div>
                <h6 class="card-title mb-0">{{ $selectedSubject->name }} Marks</h6>
                <small class="text-muted">Class: {{ optional($selectedSubject->my_class)->name }} | Year: {{ $academicYear }}</small>
            </div>
            <span class="badge badge-info">Mid + Final = Total</span>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('teacher.subject_marks.store') }}">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                <input type="hidden" name="academic_year" value="{{ $academicYear }}">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Student</th>
                            <th>ADM No</th>
                            <th>Class/Section</th>
                            <th>Mid Marks (100)</th>
                            <th>Final Marks (100)</th>
                            <th>Total</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($students as $student)
                            @php($mark = $marks->get($student->user_id))
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <img src="{{ Qs::getUserPhoto($student->user) }}" alt="{{ optional($student->user)->name }}" onerror="this.src='{{ Qs::getDefaultUserImage() }}'">
                                        <strong>{{ optional($student->user)->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $student->adm_no }}</td>
                                <td>{{ optional($student->my_class)->name }} {{ optional($student->section)->name }}</td>
                                <td><input type="number" step="0.01" min="0" max="100" name="mid_marks[{{ $student->user_id }}]" value="{{ optional($mark)->mid_marks }}" class="form-control marks-input subject-mark-input"></td>
                                <td><input type="number" step="0.01" min="0" max="100" name="final_marks[{{ $student->user_id }}]" value="{{ optional($mark)->final_marks }}" class="form-control marks-input subject-mark-input"></td>
                                <td><span class="total-badge">{{ number_format((float) optional($mark)->total_marks, 2) }}</span></td>
                                <td><input type="text" name="remarks[{{ $student->user_id }}]" value="{{ optional($mark)->remarks }}" class="form-control" placeholder="Optional teacher remarks"></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No students found for this subject/class selection.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->count())
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Mid & Final Marks</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.subject-mark-input').forEach(function (input) {
            input.addEventListener('input', function () {
                const row = this.closest('tr');
                const values = Array.from(row.querySelectorAll('.subject-mark-input')).map(function (field) {
                    return parseFloat(field.value) || 0;
                });
                row.querySelector('.total-badge').textContent = (values[0] + values[1]).toFixed(2);
            });
        });
    });
</script>

@endsection
