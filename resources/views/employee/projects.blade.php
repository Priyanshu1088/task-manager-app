@extends('employee.layout')

@section('content')

<style>
    .project-item {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .list-group-item.project-item:hover {
        background: #eef2ff !important;
        border-left: 4px solid #6366f1;
        transform: translateX(3px);
    }
    .project-number{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:28px;
        height:28px;
        border-radius:6px;
        background:#eef2ff;
        color:#4f46e5;
        font-weight:600;
        font-size:14px;
    }
</style>


<h3 class="mb-4 fw-semibold">My Projects</h3>
<div class="card shadow-sm border-0 mt-4">
    
    <div class="card-body">

        

        @if($projects->count() > 0)

        <div class="list-group">

            @foreach($projects as $index => $project)

            <a href="{{ route('employee.project.tasks',$project->id) }}"
                class="list-group-item list-group-item-action project-item">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center gap-3">

                    <span class="project-number">
                    {{ ($projects->currentPage() - 1) * $projects->perPage() + $loop->iteration }}
                    </span>

                    <span class="fw-medium">
                    {{ $project->name }}
                    </span>
                   

                    </div>

                    <div class="text-muted small">
                        View Tasks →
                    </div>

                </div>

            </a>

            @endforeach

        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $projects->links() }}
        </div>

        @else

        <p class="text-muted">No projects assigned</p>

        @endif

    </div>
</div>
@endsection