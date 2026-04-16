@extends('manager.layout')

@section('content')

<h2 class="mb-4">
    Tasks Assigned to {{ $employee->name }}
</h2>

<div class="card p-3">

    @if($tasks->count() > 0)

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Task</th>
                <th>Project</th>
                <th>Status</th>
                <th>Deadline</th>
            </tr>
        </thead>

        <tbody>

            @foreach($tasks as $task)

            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->project->name }}</td>
                <td>

                    @if($task->status == 'Pending')
                    <span class="badge bg-warning text-dark">
                        Pending
                    </span>

                    @elseif($task->status == 'In Progress')
                    <span class="badge bg-info">
                        In Progress
                    </span>

                    @elseif($task->status == 'Completed')
                    <span class="badge bg-success">
                        Completed
                    </span>

                    @endif

                </td>
                <td>{{ $task->deadline }}</td>
            </tr>

            @endforeach

        </tbody>

    </table>

    @else

    <p class="text-muted">No tasks assigned to this employee</p>

    @endif

</div>

<a href="{{ route('manager.employees') }}" class="btn btn-secondary mt-3">
    Back
</a>

@endsection