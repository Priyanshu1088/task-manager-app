@extends('manager.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Attendance History</h3>
</div>

<!-- 🔍 Filters -->

<form method="GET" action="/manager/attendance/history" class="mb-3 d-flex gap-2 align-items-center">

    <!-- Employee Dropdown -->
    <select name="employee_id" class="form-select" style="width: 200px;">
        <option value="">All Employees</option>
        @foreach($employees as $employee)
        <option value="{{ $employee->id }}"
            {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
            {{ $employee->name }}
        </option>
        @endforeach
    </select>

    <!-- Month Picker -->
    <input
        type="month"
        name="month"
        value="{{ request('month') }}"
        class="form-control"
        style="width: 200px;">

    <button type="submit" class="btn btn-primary">
        Search
    </button>

    <!-- ✅ Push this to extreme right -->
    @if(isset($presentDays) && request('employee_id') && request('month'))
    <div style="margin-left: auto;">
        <div style="
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
                padding: 10px 18px;
                border-radius: 10px;
                min-width: 150px;
                text-align: center;
                box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            ">
            <div style="font-size: 12px; opacity: 0.9;">
                Present Days
            </div>
            <div style="font-size: 20px; font-weight: 600;">
                {{ $presentDays }}/{{ $totalWorkingDays }}
            </div>
        </div>
    </div>
    @endif

</form>


<!-- 📊 Table -->
@if(request('employee_id') && request('month'))

<div class="card p-3">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>
        </thead>


        <tbody>
            @forelse($attendance as $record)
            <tr>
                <!-- Date -->
                <td>{{ $record->date->format('d M Y') }}</td>

                <!-- Employee -->
                <td>{{ $record->user->name }}</td>

                <!-- Status -->
                <td>
                    @if($record->check_in_time && $record->check_in_time > '12:00:00')
                    <span class="badge bg-danger">Late</span>
                    @else
                    <span class="badge bg-success">On Time</span>
                    @endif
                </td>

                <!-- Check In -->
                <td>
                    {{ $record->check_in_time 
                        ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') 
                        : '-' }}
                </td>

                <!-- Check Out -->
                <td>
                    {{ $record->check_out_time 
                        ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') 
                        : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No records found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 📄 Pagination -->
    <div class="mt-3">
        {{ $attendance->links() }}
    </div>

    @else

    <div class="card p-5 text-center text-muted">
        <h5>📊 No data selected</h5>
        <p>Select an employee and month to view attendance</p>
    </div>

    @endif


</div>

@endsection