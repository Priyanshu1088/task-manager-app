@php
use Illuminate\Support\Str;
@endphp
@extends('manager.layout')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ $project->name }}</h2>

    <a href="{{ route('manager.projects.show', [$project->id, 'addTask'=>1]) }}"
        class="btn btn-success">
        + Add Task
    </a>
</div>

<!-- Hidden Task Form -->
@if(request()->has('addTask') || isset($editTask))
<div id="taskForm" class="card p-4 mb-4">
    <h5 class="mb-3">
        {{ isset($editTask) ? 'Edit Task' : 'Create Task' }}
    </h5>

    <form action="{{ isset($editTask)
        ? route('manager.tasks.update',$editTask->id)
        : route('manager.tasks.store',$project->id) }}"
        method="POST">
        @csrf
        @if(isset($editTask))
        @method('PUT')
        @endif
        <div class="mb-3">
            <label>Task Name</label>

            <input type="text"
                name="name"
                class="form-control"
                placeholder="Enter task name"
                value="{{ isset($editTask) ? $editTask->name : '' }}">

        </div>

        <div class="mb-3">
            <label class="form-label">Task Description</label>
            <textarea
                name="description"
                class="form-control"
                rows="3"
                placeholder="Enter task description...">{{ old('description') }}</textarea>

            @error('description')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Deadline</label>
                <input type="date"
                    name="deadline"
                    class="form-control"
                    value="{{ isset($editTask) ? $editTask->deadline : '' }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Assign Employee</label>

                <div class="position-relative">
                    <select name="employee_id" class="form-control pe-5" required>
                        <option value="">Select Employee</option>

                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ isset($editTask) && $editTask->employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Dropdown Icon -->
                    <span style="
                        position: absolute;
                        right: 15px;
                        top: 50%;
                        transform: translateY(-50%);
                        pointer-events: none;
                    ">
                        ▼
                    </span>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-primary">
            {{ isset($editTask) ? 'Update Task' : 'Create Task' }}
        </button>

        @if(isset($editTask))
        <a href="{{ route('manager.projects.show',$project->id) }}"
            class="btn btn-secondary ms-2">
            Cancel
        </a>
        @endif
    </form>
</div>
@endif
@if(!request()->has('addTask') && !isset($editTask))
<div class="card p-4">
    @if($project->tasks->count() > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Created By</th>
                    <th>Assigned To</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($project->tasks as $task)
                <tr>
                    <td>{{ $task->name }}</td>

                    <td style="max-width: 250px;">
                        <span title="{{ $task->description ?: '-' }}">
                            {{ $task->description ? Str::limit($task->description, 40) : '-' }}
                        </span>
                    </td>

                    <td>{{ $task->created_by }}</td>

                    <td>{{ $task->employee->name }}</td>



                    <td>{{ $task->deadline }}</td>

                    <td>{{ $task->status }}</td>

                    <td class="text-end">

                        <a href="{{ route('manager.projects.show', [$project->id, 'editTask'=>$task->id]) }}"
                            class="action-icon edit-icon"
                            onclick="event.stopPropagation()">

                            <i class="bi bi-pencil-square"></i>

                        </a>

                        <form action="{{ route('manager.tasks.delete',$task->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="button"
                                class="action-icon delete-icon"
                                onclick="event.stopPropagation(); confirmDelete(this)">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    @else
    <p>No tasks yet</p>
    @endif
</div>
@endif

<script>
    function confirmDelete(button) {

        Swal.fire({
            title: "Delete Task?",
            text: "This task will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel"
        }).then((result) => {

            if (result.isConfirmed) {
                button.closest("form").submit();
            }

        });
    }
</script>



@endsection