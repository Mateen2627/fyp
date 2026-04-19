@extends('layouts.master')
@section('page_title', 'Edit User')
@section('content')
<div class="card"><div class="card-header"><h6 class="mb-0">Edit User</h6></div><div class="card-body">
<form method="POST" action="{{ route('admin.users.update', $record) }}">@csrf @method('PUT')
    @include('pages.admin.modules.users.form', ['record' => $record])
</form>
</div></div>
@endsection
