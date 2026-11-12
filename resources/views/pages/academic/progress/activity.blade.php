@extends('layouts.master')
@section('page_title', 'Activity Progress')
@section('content')
<div class="card"><div class="card-header"><h5>{{ $activity->title }} <span class="badge badge-light">Read only</span></h5></div><div class="card-body"><p>{{ $activity->description }}</p>
@if($activity->attachment_path)<a href="{{ route('academic.progress.material', [$kind, $activity->id]) }}" target="_blank" rel="noopener">View Material</a>@endif
</div><div class="table-responsive"><table class="table"><thead><tr><th>Student</th><th>Submitted</th><th>Response</th><th>File</th><th>Marks</th><th>Feedback</th></tr></thead><tbody>
@forelse($responses as $response)<tr><td>{{ optional($response->student)->name }}</td><td>{{ optional($response->submitted_at)->format('d M Y H:i') }}</td><td style="white-space:pre-wrap">{{ $kind === 'assignments' ? $response->submission_text : $response->answers }}</td><td>@if($response->attachment_path)<a target="_blank" rel="noopener" href="{{ route('academic.progress.response', [$kind, $activity->id, $response->id]) }}">View PDF</a>@endif</td><td>{{ $response->marks ?? 'Not graded' }} / {{ $activity->total_marks ?: 100 }}</td><td>{{ $response->teacher_feedback }}</td></tr>@empty<tr><td colspan="6">No submissions yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
