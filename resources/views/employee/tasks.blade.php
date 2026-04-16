@extends('employee.layout')

@section('content')

<style>
    .card div:hover{
    background:#f8f9fa;
    cursor:pointer;
    }
</style>

<h3 class="mb-4">My Projects</h3>

<div class="card p-3">

    @if($projects->count() > 0)

    @foreach($projects as $project)

    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">

        <div class="fw-semibold">
            {{ $project->name }}
        </div>

        <a href="{{ route('employee.project.tasks', $project->id) }}" class="text-primary">
            View →
        </a>

    </div>

    @endforeach

    @else

    <p class="text-muted">No projects assigned</p>

    @endif

</div>

@endsection