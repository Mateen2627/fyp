@extends('layouts.master')
@section('page_title', 'Add Semester')
@section('content')
<div class="card"><div class="card-header"><h5>Add Semester</h5></div><div class="card-body"><form method="post" action="{{ route('admin.classes.store') }}">@csrf @include('pages.admin.modules.classes.form')<button class="btn btn-primary">Create Semester</button></form></div></div>
@endsection
