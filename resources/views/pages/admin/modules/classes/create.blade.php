@extends('layouts.master')
@section('page_title', 'Create Class')
@section('content')
<div class="card"><div class="card-header"><h6>Create Class</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.classes.store') }}">@csrf @include('pages.admin.modules.classes.form')</form></div></div>
@endsection
