@extends('layouts.master')
@section('page_title', 'Edit Report')
@section('content')
<div class="card"><div class="card-header"><h6>Edit Report</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.reports.update', $record) }}">@csrf @method('PUT') @include('pages.admin.modules.reports.form', ['record' => $record])</form></div></div>
@endsection
