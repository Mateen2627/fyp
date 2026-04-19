@extends('layouts.master')
@section('page_title', 'Create '.$title)
@section('content')
<div class="card">
    <div class="card-header"><h6 class="card-title mb-0">Create {{ $title }}</h6></div>
    <div class="card-body">
        <form action="{{ route($module.'.store') }}" method="post">
            @csrf
            @include('pages.support_team.modules.form', ['module' => $module])
            <button class="btn btn-primary">Save</button>
            <a href="{{ route($module.'.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
