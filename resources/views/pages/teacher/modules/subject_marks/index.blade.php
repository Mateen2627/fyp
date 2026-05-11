@extends('layouts.master')
@section('page_title', 'Subject Mid & Final Marks')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #0ea5e9;
        --secondary: #64748b;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    /* Hero Banner */
    .marks-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        border-radius: 22px;
        padding: 30px;
        margin-bottom: 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.18);
    }

    .marks-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -15%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .marks-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .marks-hero h4 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #fff;
        position: relative;
        z-index: 2;
    }

    .marks-hero p {
        margin: 0;
        opacity: .92;
        font-size: 14px;
        position: relative;
        z-index: 2;
    }

    /* Cards */
    .modern-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .modern-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    .modern-card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modern-card-title i {
        color: var(--primary);
    }

    .modern-card-body {
        padding: 24px;
    }

    /* Fix Panel Buttons */
    .header-elements-inline {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
    }

    .header-elements-inline .header-elements {
        margin-left: auto;
    }

    .header-elements-inline .list-icons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-elements-inline .list-icons a {
        color: #64748b !important;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .header-elements-inline .list-icons a:hover {
        color: var(--primary) !important;
        transform: scale(1.1);
    }

    /* Filter Section */
    .marks-filter {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
    }

    .marks-filter label {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        height: 48px;
        border-radius: 12px;
        border: 1px solid #dbe2ea;
        box-shadow: none !important;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08) !important;
    }

    /* Buttons */
    .btn-modern {
        height: 48px;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #fff;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(79, 70, 229, 0.25);
        color: #fff;
    }

    .btn-success-modern {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        padding: 12px 24px;
    }

    .btn-success-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(16, 185, 129, 0.25);
        color: #fff;
    }

    /* Table */
    .table-modern {
        margin: 0;
    }

    .table-modern thead th {
        background: #f8fafc;
        border: none;
        padding: 16px;
        font-size: 13px;
        font-weight: 700;
        color: var(--dark);
        white-space: nowrap;
    }

    .table-modern tbody td {
        vertical-align: middle;
        padding: 16px;
        border-top: 1px solid #f1f5f9;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: #fafbff;
    }

    /* Student Cell */
    .student-cell {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 240px;
    }

    .student-cell img {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    .student-name {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 2px;
    }

    .student-sub {
        font-size: 12px;
        color: var(--gray);
    }

    /* Marks Input */
    .marks-input {
        width: 110px;
        text-align: center;
        font-weight: 700;
    }

    /* Total Badge */
    .total-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 75px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4338ca;
        font-weight: 700;
        font-size: 13px;
    }

    /* Info Badge */
    .info-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 10px 16px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    @media (max-width: 768px) {

        .marks-hero {
            padding: 24px;
        }

        .marks-hero h4 {
            font-size: 22px;
        }

        .modern-card-body {
            padding: 18px;
        }

        .student-cell {
            min-width: 200px;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 12px;
        }
    }
</style>

<div class="marks-hero">

    <h4>
        <i class="fas fa-clipboard-check"></i>
        Subject Mid & Final Marks
    </h4>

    <p>
        Choose one of your assigned subjects and manage mid & final examination marks for students professionally.
    </p>

</div>

<!-- Filter Card -->
<div class="card modern-card">

    <div class="card-header header-elements-inline modern-card-header">

        <h6 class="modern-card-title" style="color: #4f46e5;">
            <i class="fas fa-filter"></i>
            Select Subject
        </h6>

        {!! Qs::getPanelOptions() !!}

    </div>

    <div class="modern-card-body">

        <form method="get"
              action="{{ route('teacher.subject_marks.index') }}"
              class="marks-filter">

            <div class="row align-items-end">

                <div class="col-md-4 mb-3">

                    <label>Subject</label>

                    <select name="subject_id"
                            class="form-control"
                            required>

                        @forelse($subjects as $subject)

                            <option value="{{ $subject->id }}"
                                {{ optional($selectedSubject)->id === $subject->id ? 'selected' : '' }}>

                                {{ $subject->name }} - {{ optional($subject->my_class)->name }}

                            </option>

                        @empty

                            <option value="">
                                No subjects assigned
                            </option>

                        @endforelse

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label>Section</label>

                    <select name="section_id" class="form-control">

                        <option value="">
                            All Sections
                        </option>

                        @foreach($sections as $section)

                            <option value="{{ $section->id }}"
                                {{ (int) $selectedSectionId === (int) $section->id ? 'selected' : '' }}>

                                {{ $section->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label>Academic Year</label>

                    <input type="text"
                           name="academic_year"
                           value="{{ $academicYear }}"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-2 mb-3">

                    <button type="submit"
                            class="btn btn-modern btn-primary-modern btn-block">

                        <i class="fas fa-users"></i>
                        Load

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@if($selectedSubject)

<div class="card modern-card">

    <div class="card-header header-elements-inline modern-card-header">

        <div>

            <h6 class="modern-card-title mb-2">
                <i class="fas fa-book-open"></i>
                {{ $selectedSubject->name }} Marks
            </h6>

            <small class="text-muted">
                Class:
                {{ optional($selectedSubject->my_class)->name }}
                |
                Year:
                {{ $academicYear }}
            </small>

        </div>

        <div class="d-flex align-items-center" style="gap:10px;">

            <span class="info-badge">
                Mid + Final = Total
            </span>

            {!! Qs::getPanelOptions() !!}

        </div>

    </div>

    <div class="modern-card-body">

        <form method="post"
              action="{{ route('teacher.subject_marks.store') }}">

            @csrf

            <input type="hidden"
                   name="subject_id"
                   value="{{ $selectedSubject->id }}">

            <input type="hidden"
                   name="section_id"
                   value="{{ $selectedSectionId }}">

            <input type="hidden"
                   name="academic_year"
                   value="{{ $academicYear }}">

            <div class="table-responsive">

                <table class="table table-modern">

                    <thead>

                    <tr>
                        <th>Student</th>
                        <th>ADM No</th>
                        <th>Class/Section</th>
                        <th>Mid Marks</th>
                        <th>Final Marks</th>
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

                                    <img src="{{ Qs::getUserPhoto($student->user) }}"
                                         alt="{{ optional($student->user)->name }}"
                                         onerror="this.src='{{ Qs::getDefaultUserImage() }}'">

                                    <div>

                                        <div class="student-name">
                                            {{ optional($student->user)->name }}
                                        </div>

                                        <div class="student-sub">
                                            Student Record
                                        </div>

                                    </div>

                                </div>

                            </td>

                            <td>
                                <strong>{{ $student->adm_no }}</strong>
                            </td>

                            <td>
                                {{ optional($student->my_class)->name }}
                                {{ optional($student->section)->name }}
                            </td>

                            <td>

                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       name="mid_marks[{{ $student->user_id }}]"
                                       value="{{ optional($mark)->mid_marks }}"
                                       class="form-control marks-input subject-mark-input">

                            </td>

                            <td>

                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       name="final_marks[{{ $student->user_id }}]"
                                       value="{{ optional($mark)->final_marks }}"
                                       class="form-control marks-input subject-mark-input">

                            </td>

                            <td>

                                <span class="total-badge">
                                    {{ number_format((float) optional($mark)->total_marks, 2) }}
                                </span>

                            </td>

                            <td>

                                <input type="text"
                                       name="remarks[{{ $student->user_id }}]"
                                       value="{{ optional($mark)->remarks }}"
                                       class="form-control"
                                       placeholder="Optional teacher remarks">

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-4">
                                No students found for this selection.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            @if($students->count())

                <div class="text-right mt-4">

                    <button type="submit"
                            class="btn btn-modern btn-success-modern">

                        <i class="fas fa-save"></i>
                        Save Mid & Final Marks

                    </button>

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

                const values = Array.from(
                    row.querySelectorAll('.subject-mark-input')
                ).map(function (field) {
                    return parseFloat(field.value) || 0;
                });

                row.querySelector('.total-badge').textContent =
                    (values[0] + values[1]).toFixed(2);

            });

        });

    });
</script>

@endsection