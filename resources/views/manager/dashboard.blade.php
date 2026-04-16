@extends('manager.layout')

@section('content')

<style>
    .dashboard-card {
        border-radius: 12px;
        padding: 25px;
        color: white;
        transition: all 0.25s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .projects-card {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .tasks-card {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .employees-card {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .card-icon {
        font-size: 28px;
        opacity: 0.9;
    }

    .card-number {
        font-size: 32px;
        font-weight: 700;
    }

    .recent-project-item {
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .recent-project-item:hover {
        background: #f1f5ff;
        transform: translateX(5px);
        border-left: 4px solid #6366f1;
    }

    .task-item {
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .task-item:hover {
        background: #f8fafc;
        transform: translateX(5px);
        border-left: 4px solid #22c55e;
    }

    h3 {
        letter-spacing: 0.5px;
    }

    .attendance-row {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        border-left: 4px solid #22c55e;
    }

    .attendance-card {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        color: white;
    }
</style>

<div class="mb-4">

    <h3 class="fw-bold">
        {{ $greeting }}, {{ session('user_name') ?? 'Manager' }} 👋
    </h3>

    <p class="text-muted mb-0">
        Here is your workspace overview for today
    </p>

</div>

<div class="row g-4 mb-4">

    <!-- Total Projects -->
    <div class="col-md-6 mb-4">
        <a href="{{ route('manager.projects') }}" class="text-decoration-none">
            <div class="dashboard-card projects-card">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div>Total Projects</div>
                        <div class="card-number">{{ $totalProjects }}</div>
                    </div>

                    <div class="card-icon">📁</div>
                </div>

            </div>
        </a>
    </div>

    <!-- Latest Tasks -->
    <div class="col-md-6 mb-4">
        <a href="{{ route('manager.tasks') }}" class="text-decoration-none">
            <div class="dashboard-card tasks-card">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div>Latest Tasks</div>
                        <div class="card-number">10</div>
                    </div>

                    <div class="card-icon">✅</div>
                </div>

            </div>
        </a>
    </div>

    <!-- Total Employees -->
    <div class="col-md-6 mb-4">
        <a href="{{ route('manager.employees') }}" class="text-decoration-none">
            <div class="dashboard-card employees-card">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div>Total Employees</div>
                        <div class="card-number">{{ $totalEmployees }}</div>
                    </div>

                    <div class="card-icon">👨‍💼</div>
                </div>

            </div>
        </a>
    </div>

    <!-- Attendance (NEW CARD 🔥) -->
    <div class="col-md-6 mb-4">
        <a href="{{ route('manager.attendance') }}" class="text-decoration-none">
            <div class="dashboard-card attendance-card">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div>Today's Attendance</div>

                        <!-- FIXED LINE -->
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-number mb-0">
                                {{ $attendances->count() }}
                            </div>
                            <small class="text-light mb-0">
                                employees checked in today
                            </small>
                        </div>

                    </div>

                    <div class="card-icon">📊</div>
                </div>

            </div>
        </a>
    </div>

</div>



<div class="card shadow-sm border-0 mt-4">

    <div class="card-body">

        <h5 class="mb-4 fw-semibold">Recent Projects</h5>

        @if($recentProjects->count() > 0)

        <div class="list-group">

            @foreach($recentProjects as $project)

            <a href="{{ route('manager.projects.show',$project->id) }}"
                class="list-group-item list-group-item-action recent-project-item">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="fw-medium">
                        {{ $project->name }}
                    </div>

                    <div class="text-muted small">
                        View →
                    </div>

                </div>

            </a>

            @endforeach

        </div>

        @else

        <p class="text-muted">No projects yet</p>

        @endif

    </div>

</div>

<div class="card shadow-sm border-0 mt-4">

    <div class="card-body">

        <h5 class="mb-4 fw-semibold">Recent Tasks</h5>

        @if($recentTasks->count() > 0)

        <div class="list-group">

            @foreach($recentTasks as $task)

            <div class="list-group-item task-item">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="fw-medium">
                            {{ $task?->name }} ({{ $task->project?->name }})
                        </div>

                        <div class="text-muted small">
                            Assigned to {{ @$task->employee?->name }}
                        </div>

                    </div>

                    <div>

                        @if($task->status == 'Completed')
                        <span class="badge bg-success">
                            Completed
                        </span>
                        @else
                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>
                        @endif

                    </div>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <p class="text-muted">No tasks yet</p>

        @endif

    </div>

</div>
<h4 class="mt-4 mb-3">Today's Tasks In Progress</h4>

<div class="card shadow-sm border-0">
    <div class="card-body">

        @if($workingTasks->count() > 0)

        @foreach($workingTasks as $task)

        <div class="d-flex justify-content-between align-items-start py-3 border-bottom">

            <div>

                <h6 class="mb-1 fw-bold">
                    {{ $task->name }}

                    <span class="text-muted fw-normal">
                        ({{ $task->project->name ?? '-' }})
                    </span>
                </h6>

                <div class="text-primary small fw-semibold">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ $task->employee->name }} is working on this
                </div>

                @if($task->comment)

                <div class="comment-box mt-2">
                    <i class="bi bi-chat-left-text me-2"></i>
                    {{ $task->comment }}
                </div>

                @endif

            </div>

            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                In Progress
            </span>

        </div>

        @endforeach

        @else

        <div class="text-muted">No tasks currently in progress</div>

        @endif

    </div>
</div>

</div>
@endsection