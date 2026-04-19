@extends('layouts.master')
@section('page_title', 'Edit '.$title)
@section('content')
<div class="card">
    <div class="card-header"><h6 class="card-title mb-0">Edit {{ $title }}</h6></div>
    <div class="card-body">
        <form action="{{ route($module.'.update', $record->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('pages.support_team.modules.form', ['module' => $module, 'record' => $record])
            <button class="btn btn-primary">Update</button>
            <a href="{{ route($module.'.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
