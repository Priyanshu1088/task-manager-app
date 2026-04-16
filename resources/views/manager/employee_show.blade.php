@extends('manager.layout')

@section('content')


<h2 class="mb-4">Employee Details</h2>

<div class="card p-4">

    <p><strong>Name:</strong> {{ $employee->name }}</p>

    <p><strong>Email:</strong> {{ $employee->email }}</p>

    <p><strong>Mobile:</strong>{{ $employee->mobile }}</p>

    <p><strong>Role:</strong> {{ $employee->role }}</p>

    <p><strong>DOB:</strong> {{ $employee->dob }}</p>

    <p><strong>Joined:</strong> {{ $employee->created_at->format('d M Y') }}</p>

    <a href="{{ route('manager.employee.tasks',$employee->id) }}"
        class="btn btn-primary mt-3">

        <i class="bi bi-list-task"></i>
        All Tasks Assigned to {{ $employee->name }}

    </a>

    <a href="{{ route('manager.employees') }}" class="btn btn-secondary mt-3">
        Back
    </a>

</div>



@endsection