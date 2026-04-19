@extends('layouts.master')
@section('page_title', 'Create Setting')
@section('content')
<div class="card"><div class="card-header"><h6>Create Setting</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.system-settings.store') }}">@csrf @include('pages.admin.modules.settings.form')</form></div></div>
@endsection
