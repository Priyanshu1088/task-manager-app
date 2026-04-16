@extends('manager.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <!-- LEFT: Heading -->
    <h3 class="mb-0">Today's Attendance</h3>

    <!-- RIGHT: Filters -->
    <form method="GET" id="filter-form" class="d-flex gap-2">

        <!-- Search -->
        <div class="input-group" style="width: 250px;">
            <span class="input-group-text bg-light">🔍</span>
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search..."
                value="{{ request('search') }}">
        </div>

        <!-- Status -->
        <select name="status" class="form-select" style="width: 160px;">
            <option value="">All</option>
            <option value="on_time" {{ request('status') == 'on_time' ? 'selected' : '' }}>
                On Time
            </option>
            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>
                Late
            </option>
        </select>



    </form>

    <a href="/manager/attendance/history" class="btn btn-primary">
        View Attendance History
    </a>

</div>

<div class="card p-3">

    <table class="table">
    

        <h5 class="text-success mb-3">
            🟢 Present ({{ $presentEmployees->count() }})
        </h5>

        <table class="table mb-4">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Check In Time</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($presentEmployees as $emp)

                @php
                $att = $attendances[$emp->id];
                @endphp

                <tr>
                    <td>{{ $emp->name }}</td>

                    <td>{{ \Carbon\Carbon::parse($att->check_in_time)->format('h:i A') }}</td>

                    <td>
                        @if($att->check_out_time)
                        {{ \Carbon\Carbon::parse($att->check_out_time)->format('h:i A') }}
                        @else
                        <span class="text-muted">--</span>
                        @endif
                    </td>

                    <td>
                        @if($att->check_in_time > '09:45:00')
                        <span class="badge bg-danger">Late</span>
                        @else
                        <span class="badge bg-success">On Time</span>
                        @endif
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>

        <h5 class="text-danger mb-3">
            🔴 Absent ({{ $absentEmployees->count() }})
        </h5>

        <table class="table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Check In Time</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($absentEmployees as $emp)

                <tr>
                    <td>{{ $emp->name }}</td>

                    <td><span class="text-muted">--</span></td>
                    <td><span class="text-muted">--</span></td>

                    <td>
                        <span class="badge bg-secondary">Didn’t check in</span>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </table>


</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById('filter-form');
        const status = document.querySelector('[name="status"]');
        const search = document.querySelector('[name="search"]');

        // 🔽 Dropdown change → instant submit
        status.addEventListener('change', function() {
            form.submit();
        });

        // 🔍 Search → delay submit (debounce)
        let timeout = null;

        search.addEventListener('keyup', function() {
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                form.submit();
            }, 500); // wait 0.5 sec after typing
        });

    });
</script>

@endsection