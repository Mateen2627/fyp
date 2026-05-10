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
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $s)
                    <tr data-search="{{ strtolower($s->user->name.' '.$s->adm_no.' '.optional($s->my_class)->name.' '.optional($s->section)->name.' '.$s->user->email) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ $s->user->photo }}" alt="photo"></td>
                        <td>{{ $s->user->name }}</td>
                        <td>{{ $s->adm_no }}</td>
                        <td>{{ $s->my_class->name.' '.$s->section->name }}</td>
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
