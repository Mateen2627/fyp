@extends('layouts.master')
@section('page_title', 'My Children')
@section('content')

<style>
    .table-filter-bar { display: flex; flex-wrap: wrap; gap: 12px; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .table-filter-bar .form-control { max-width: 300px; }
</style>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">My Children</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="table-filter-bar">
            <input type="search" class="form-control" id="children-table-search" placeholder="Search child, ADM no, class, or email">
        </div>

        <div class="card-body">
            <table class="table datatable-button-html5-columns" id="children-table">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>ADM_No</th>
                    <th>Section</th>
                    <th>Attendance</th>
                    <th>Present %</th>
                    <th>Mid/Final Totals</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $s)
                    <tr data-search="{{ strtolower($s->user->name.' '.$s->adm_no.' '.optional($s->my_class)->name.' '.optional($s->section)->name.' '.$s->user->email) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ Qs::getUserPhoto($s->user) }}" alt="photo"></td>
                        <td>{{ $s->user->name }}</td>
                        <td>{{ $s->adm_no }}</td>
                        <td>{{ $s->my_class->name.' '.$s->section->name }}</td>
                        @php
                            $attendanceTotal = (int) ($attendanceCounts[$s->user_id] ?? 0);
                            $presentTotal = (int) ($presentCounts[$s->user_id] ?? 0);
                            $presentPct = $attendanceTotal > 0 ? round(($presentTotal / $attendanceTotal) * 100, 1) : 0;
                            $totals = $markTotals[$s->user_id] ?? null;
                        @endphp
                        <td>{{ $attendanceTotal }}</td>
                        <td>{{ $presentPct }}%</td>
                        <td>
                            Mid: {{ number_format((float) ($totals->mid_total ?? 0), 2) }}<br>
                            Final: {{ number_format((float) ($totals->final_total ?? 0), 2) }}
                        </td>
                        <td>{{ $s->user->email }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="dropdown-item"><i class="icon-eye"></i> View Profile</a>
                                        <a target="_blank" href="{{ route('marks.year_selector', Qs::hash($s->user->id)) }}" class="dropdown-item"><i class="icon-check"></i> Marksheet</a>
                                        <a href="{{ route('parent.attendance.show', $s->id) }}" class="dropdown-item"><i class="icon-calendar3"></i> Attendance</a>
                                        <a href="{{ route('parent.academics.show', $s->id) }}" class="dropdown-item"><i class="icon-books"></i> Academics (Marks/Assignments/Quizzes)</a>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    {{--Student List Ends--}}


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('children-table-search');
        const table = document.getElementById('children-table');

        if (search && table) {
            search.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                table.querySelectorAll('tbody tr').forEach(function (row) {
                    row.style.display = !value || (row.dataset.search || row.textContent.toLowerCase()).includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>

@endsection
