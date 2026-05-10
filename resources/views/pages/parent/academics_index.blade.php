@extends('layouts.master')
@section('page_title', 'Children Academics')
@section('content')
<div class="card">
  <div class="card-header"><h6 class="mb-0">Choose Child - Academics</h6></div>
  <div class="card-body">
    <div class="row">
      @foreach($students as $s)
      <div class="col-md-4 mb-3">
        <div class="card border">
          <div class="card-body text-center">
            <img src="{{ Qs::getUserPhoto($s->user) }}" class="rounded-circle mb-2" style="width:60px;height:60px;" alt="photo">
            <h6 class="mb-1">{{ $s->user->name }}</h6>
            <div class="text-muted mb-2">{{ optional($s->my_class)->name }} {{ optional($s->section)->name }}</div>
            <a href="{{ route('parent.academics.show', $s->id) }}" class="btn btn-primary btn-sm">Open Academics</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
