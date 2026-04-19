@extends('layouts.master')
@section('page_title', 'Create User')
@section('content')
<div class="card"><div class="card-header"><h6 class="mb-0">Create User</h6></div><div class="card-body">
<form method="POST" action="{{ route('admin.users.store') }}">@csrf
    @include('pages.admin.modules.users.form')
</form>
</div></div>
@endsection
