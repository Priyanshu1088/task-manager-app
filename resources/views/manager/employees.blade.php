@extends('manager.layout')

@section('content')

<style>
    .employee-row {
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .employee-row:hover {
        background: linear-gradient(90deg, #eef2ff, #f5f7ff);
        transform: scale(1.01);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #2563eb;
    }

    .employee-row:hover td {
        color: #2563eb;
        font-weight: 600;
    }

    .action-icon {
        border: none;
        background: none;
        font-size: 18px;
        margin-right: 10px;
        cursor: pointer;
        transition: 0.25s ease;
        padding: 6px;
        border-radius: 6px;
    }

    /* EDIT ICON */
    .edit-icon {
        color: #3b82f6;
    }

    .edit-icon:hover {
        background: #e0edff;
        color: #1d4ed8;
        transform: scale(1.15);
    }

    /* DELETE ICON */
    .delete-icon {
        color: #ef4444;
    }

    .delete-icon:hover {
        background: #ffe5e5;
        color: #b91c1c;
        transform: scale(1.15);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Employees</h2>

    <form method="GET" action="{{ route('manager.employees') }}" class="d-flex">

        <input
            type="text"
            name="search"
            id="employeeSearch"
            class="form-control me-2"
            placeholder="Search employee..."
            value="{{ request('search') }}">

        <button class="btn btn-outline-primary me-2">
            <i class="bi bi-search"></i>
        </button>

        @if(request('search'))
        <a href="{{ route('manager.employees') }}" class="btn btn-outline-secondary">
            Clear
        </a>
        @endif

    </form>
    <a href="{{ route('register') }}" class="btn btn-primary">
        + Add New
    </a>
</div>

<div class="card">
    <div class="card-body">

        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($employees as $index => $employee)

                        <tr class="employee-row"
                            onclick="window.location='{{ route('manager.employees.show',$employee->id) }}'">

                            <td>{{ $index + 1 }}</td>
                            

                            <td>{{ $employee->name }}</td>

                            <td onclick="event.stopPropagation()">

                                <a href="{{ route('manager.employees.edit', $employee->id) }}"
                                    class="action-icon edit-icon"
                                    onclick="event.stopPropagation()">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <form action="{{ route('manager.employees.delete', $employee->id) }}"
                                    method="POST"
                                    style="display:inline-block;">

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
        <p>No employees found.</p>
        @endif

    </div>
</div>

<script>
    function confirmDelete(button) {

        Swal.fire({
            title: "Delete Employee?",
            text: "This employee will be permanently deleted!",
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


    document.querySelector('input[name="search"]').addEventListener('input', function() {

        if (this.value.trim() === '') {
            window.location.href = "{{ route('manager.employees') }}";
        }

    });
</script>

@endsection