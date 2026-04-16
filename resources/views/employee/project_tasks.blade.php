@extends('employee.layout')

@section('content')
@php
$currentStatus = request('status', 'all');
@endphp



   <div class="d-flex justify-content-between align-items-center mb-5">

        <h2 class="mb-0">{{ $project->name }} Tasks</h2>

        <a href="{{ route('employee.tasks.create', $project->id) }}" 
        class="btn btn-primary d-flex align-items-center gap-2">

            <i class="bi bi-plus-circle"></i> Add Task

        </a>

    </div>

<div class="row mb-4">

    <div class="col-md-3">
        <a href="{{ route('employee.project.tasks', [$project->id,'status'=>'all']) }}" class="text-decoration-none">
            <div class="task-card card text-center p-3 navy-card text-white {{ $currentStatus=='all' ? 'active-card' : '' }}">
                <h6>All Tasks</h6>
                <h3>{{ $totalTasks }}</h3>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('employee.project.tasks', [$project->id,'status'=>'Pending']) }}" class="text-decoration-none">
            <div class="task-card card text-center p-3 bg-warning text-white {{ $currentStatus=='Pending' ? 'active-card' : '' }}">
                <h6>Pending</h6>
                <h3>{{ $pendingTasks }}</h3>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('employee.project.tasks', [$project->id,'status'=>'In Progress']) }}" class="text-decoration-none">
            <div class="task-card card text-center p-3 bg-info text-white {{ $currentStatus=='In Progress' ? 'active-card' : '' }}">
                <h6>In Progress</h6>
                <h3>{{ $inProgressTasks }}</h3>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('employee.project.tasks', [$project->id,'status'=>'Completed']) }}" class="text-decoration-none">
            <div class="task-card card text-center p-3 bg-success text-white {{ $currentStatus=='Completed' ? 'active-card' : '' }}">
                <h6>Completed</h6>
                <h3>{{ $completedTasks }}</h3>
            </div>
        </a>
    </div>

</div>


@if($tasks->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Assigned date</th>
                    <th>Due date</th>
                    <th>Completed date</th>

                    
                </tr>
            </thead>

            <tbody>

                @foreach($tasks as $task)

                <tr>
                    <td>{{ $task->name }}</td>
                    <td>

                        @if($currentStatus == 'all')

                        <form action="{{ route('employee.task.update',$task->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">

                                <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>
                                    In Progress
                                </option>

                                <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                            </select>

                        </form>

                        @else

                        <span class="badge 
                        {{ $task->status == 'Pending' ? 'bg-warning' : '' }}
                        {{ $task->status == 'In Progress' ? 'bg-info' : '' }}
                        {{ $task->status == 'Completed' ? 'bg-success' : '' }}">
                            {{ $task->status }}
                        </span>

                        @endif

                    </td>
                    <td>{{ $task->created_at->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                    <td>
                        {{ $task->completed_at ? $task->completed_at->format('d M Y') : '-' }}
                    </td>
                    
                </tr>

                @endforeach

            </tbody>

        </table>
    </div>
@else

<div class="text-center mt-4">
    <p class="text-muted">No tasks found for this filter</p>
</div>

@endif

@endsection