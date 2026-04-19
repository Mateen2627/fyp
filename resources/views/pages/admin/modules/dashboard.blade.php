@extends('layouts.master')
@section('page_title', 'Admin Module Dashboard')
@section('content')
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Admin Dashboard</h6></div>
        <div class="card-body">
            <div class="row">
                @foreach($stats as $label => $count)
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3">
                            <h3 class="mb-0">{{ $count }}</h3>
                            <small class="text-muted text-uppercase">{{ str_replace('_', ' ', $label) }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
