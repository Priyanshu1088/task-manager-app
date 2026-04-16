@extends('employee.layout')

@section('content')

<h3 class="mb-4">Add Task - {{ $project->name }}</h3>

<div class="card p-4">

    <form action="{{ route('employee.tasks.store', $project->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Task Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea 
                name="description" 
                class="form-control" 
                rows="4"
                placeholder="Enter task details..."></textarea>
        </div>

        <button class="btn btn-success">
            Create Task
        </button>

        <a href="{{ route('employee.project.tasks',$project->id) }}"
            class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection