@extends('layouts.master')
@section('page_title', 'Edit Timetable')
@section('content')
<div class="card"><div class="card-header"><h5>Edit Timetable</h5></div><div class="card-body"><form method="post" action="{{ route('ttr.update', $record->id) }}">@csrf @method('PUT') @include('pages.academic.timetables.fields')<button class="btn btn-primary">Save Timetable</button></form></div></div>
@endsection
