@extends('layouts.master')
@section('page_title', 'Edit Semester')
@section('content')
<div class="card"><div class="card-header"><h5>Edit Semester</h5></div><div class="card-body"><form method="post" action="{{ route('admin.classes.update', $record) }}">@csrf @method('PUT') @include('pages.admin.modules.classes.form')<button class="btn btn-primary">Save Semester</button></form></div></div>
@endsection
