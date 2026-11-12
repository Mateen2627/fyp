@extends('layouts.master')
@section('page_title', 'Add User')
@section('content')
<div class="card"><div class="card-header"><h5>Add User</h5></div><div class="card-body"><form method="post" action="{{ route('admin.users.store') }}">@csrf @include('pages.admin.modules.users.form')<button class="btn btn-primary">Create User</button></form></div></div>
@endsection
