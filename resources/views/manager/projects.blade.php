@extends('manager.layout')

@section('content')

<style>
    .project-row {
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .project-row:hover {
        background: linear-gradient(90deg, #eef2ff, #f5f7ff);
        transform: scale(1.01);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .project-row:hover td {
        color: #2563eb;
        font-weight: 600;
    }

    .project-row:hover {
        background: linear-gradient(90deg, #eef2ff, #f5f7ff);
        border-left: 4px solid #2563eb;
    }
    .form-control{
        border-radius:8px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Projects</h2>

    <form method="GET" action="{{ route('manager.projects') }}" class="d-flex">

        <input
            type="text"
            name="search"
            class="form-control me-2"
            placeholder="Search project..."
            value="{{ request('search') }}">

        <button class="btn btn-outline-primary me-2">
            <i class="bi bi-search"></i>
        </button>

        @if(request('search'))
        <a href="{{ route('manager.projects') }}" class="btn btn-outline-secondary">
            Clear
        </a>
        @endif

    </form>
    <a href="{{ route('manager.projects.create') }}" class="btn btn-primary">
        + Add New Project
    </a>
</div>

@if($projects->count() > 0)

<div class="card">
    <div class="card-body">
        <div class="table-responsive">    
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th width="120" class="text-center">Actions</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach($projects as $index => $project)
                    <tr class="project-row"
                        onclick="window.location='{{ route('manager.projects.show', $project->id) }}'">

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $project->name }}</td>

                        <td class="text-end">

                            <a href="{{ route('manager.projects.create',['edit'=>$project->id]) }}"
                                class="action-icon edit-icon"
                                onclick="event.stopPropagation()">

                                <i class="bi bi-pencil-square"></i>

                            </a>

                            <form action="{{ route('manager.projects.delete',$project->id) }}"
                                method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="button"
                                    class="action-icon delete-icon"
                                    onclick="event.stopPropagation(); confirmProjectDelete(this)">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@else

<div class="text-center mt-5">
    <h4>No projects found</h4>
    <p>Start by creating your first project.</p>
    <a href="{{ route('manager.projects.create') }}" class="btn btn-primary">
        + Create Project
    </a>
</div>

@endif

<script>
    function confirmProjectDelete(button) {

        Swal.fire({
            title: "Delete Project?",
            text: "This will permanently remove the project and all its tasks",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel"
        }).then((result) => {

            if (result.isConfirmed) {
                button.closest("form").submit();
            }

        });

    }

    document.querySelector('input[name="search"]').addEventListener('input', function() {

        if (this.value === '') {
            window.location.href = "{{ route('manager.projects') }}";
        }

    });
</script>

@endsection