@extends('manager.layout')

@section('content')

@php
$currentStatus = request('status', 'all');
@endphp

<h2 class="mb-4">Latest 10 Tasks</h2>

<!-- FILTER CARDS (DASHBOARD STYLE) -->

<div class="row mb-4">


<div class="col-md-3">
    <a href="{{ route('manager.tasks', ['status'=>'all']) }}" class="text-decoration-none">
        <div class="task-card text-center p-3 navy-card text-white {{ $currentStatus=='all' ? 'active-card' : '' }}">
            <h6>Tasks</h6>
            <h3>{{ $totalTasks }}</h3>
        </div>
    </a>
</div>

<div class="col-md-3">
    <a href="{{ route('manager.tasks', ['status'=>'Pending']) }}" class="text-decoration-none">
        <div class="task-card card text-center p-3 bg-warning text-white {{ $currentStatus=='Pending' ? 'active-card' : '' }}">
            <h6>Pending</h6>
            <h3>{{ $pendingTasks }}</h3>
        </div>
    </a>
</div>

<div class="col-md-3">
    <a href="{{ route('manager.tasks', ['status'=>'In Progress']) }}" class="text-decoration-none">
        <div class="task-card card text-center p-3 bg-info text-white {{ $currentStatus=='In Progress' ? 'active-card' : '' }}">
            <h6>In Progress</h6>
            <h3>{{ $inProgressTasks }}</h3>
        </div>
    </a>
</div>

<div class="col-md-3">
    <a href="{{ route('manager.tasks', ['status'=>'Completed']) }}" class="text-decoration-none">
        <div class="task-card card text-center p-3 bg-success text-white {{ $currentStatus=='Completed' ? 'active-card' : '' }}">
            <h6>Completed</h6>
            <h3>{{ $completedTasks }}</h3>
        </div>
    </a>
</div>


</div>

<!-- TASK LIST (EMPLOYEE STYLE ROWS) -->

@if($tasks->count() > 0)


@foreach($tasks as $task)

<div class="task-row 
    {{ $task->status == 'Pending' ? 'pending' : '' }}
    {{ $task->status == 'In Progress' ? 'in-progress' : '' }}
    {{ $task->status == 'Completed' ? 'completed' : '' }}">

    <div>
        <h6 class="mb-1 fw-semibold">
            {{ $task->name }} ({{ $task->project->name ?? 'No Project' }})
        </h6>

        <small class="text-muted">
            Assigned to {{ $task->employee->name ?? 'N/A' }}
        </small>

        <p class="text-muted small mb-0">
            Assigned on {{ $task->created_at->format('d M Y, h:i A') }}
        </p>

    </div>

    <span class="badge 
        {{ $task->status == 'Pending' ? 'bg-warning text-dark' : '' }}
        {{ $task->status == 'In Progress' ? 'bg-info text-white' : '' }}
        {{ $task->status == 'Completed' ? 'bg-success text-white' : '' }}">
        {{ $task->status }}
    </span>

</div>

@endforeach


@else


<div class="text-center mt-4">
    <p class="text-muted">No tasks found</p>
</div>


@endif

@endsection

<style>

/* NAVY CARD */
.navy-card {
    background: #0f172a;
}

/* FILTER CARDS */
.task-card {
    border-radius: 12px;
    cursor: pointer;
    transition: 0.25s ease;
}

.task-card:hover {
    transform: scale(1.03);
}

.active-card {
    box-shadow: 0 0 0 3px #3b82f6;
}

/* TASK ROW STYLE */
.task-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 14px 18px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    margin-bottom: 12px;
    transition: all 0.25s ease;
    cursor: pointer;
    border-left: 5px solid transparent;
}

/* HOVER */
.task-row:hover {
    background: linear-gradient(90deg, #eef2ff, #f5f7ff);
    transform: scale(1.01);
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

/* STATUS COLORS */
.task-row.pending {
    border-left-color: #f59e0b;
}

.task-row.in-progress {
    border-left-color: #0dcaf0;
}

.task-row.completed {
    border-left-color: #198754;
}

.task-item {
    background: #f8f9fa;
    border-left: 5px solid #0dcaf0;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 12px;

    min-height: 80px; /* 🔥 THIS FIXES YOUR ISSUE */
    display: flex;
    justify-content: space-between;
    align-items: center;
}

</style>
