@extends('layouts.master')
@section('page_title', 'Edit Class')
@section('content')
<div class="card"><div class="card-header"><h6>Edit Class</h6></div><div class="card-body"><form method="POST" action="{{ route('admin.classes.update', $record) }}">@csrf @method('PUT') @include('pages.admin.modules.classes.form', ['record' => $record])</form></div></div>
@endsection
