@extends('layouts.master')
@section('page_title', $title.' Details')
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title mb-0">{{ $title }} Details</h6>
        <div class="header-elements"><a href="{{ route($module.'.index') }}" class="btn btn-light btn-sm">Back</a></div>
    </div>
    <div class="card-body">
        <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($record->toArray(), JSON_PRETTY_PRINT) }}</pre>
    </div>
</div>
@endsection
