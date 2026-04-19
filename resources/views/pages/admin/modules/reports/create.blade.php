@extends('layouts.master')
@section('page_title', 'Create Report')
@section('content')
<div class="card"><div class="card-header"><h6>Create Report</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.reports.store') }}">@csrf @include('pages.admin.modules.reports.form')</form></div></div>
@endsection
