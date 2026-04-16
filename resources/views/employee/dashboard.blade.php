@extends('employee.layout')

@section('content')

@php
$hour = date('H');

if ($hour < 12) {
    $greeting="Good Morning" ;
    } elseif ($hour < 17) {
    $greeting="Good Afternoon" ;
    } else {
    $greeting="Good Evening" ;
    }
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            {{ $greeting }}, {{ $employee->name }} 👋
        </h2>

        <p class="text-muted mb-0">
            Here is your workspace overview for today
        </p>
    </div>

    @if(!$checkedIn)
    <button id="checkin-btn" class="btn btn-success">✔ Check In</button>

    @elseif($checkedIn && !$checkedOut)
    <button id="checkout-btn" class="btn btn-danger">⏹ Check Out</button>

    @else
    <button class="btn btn-secondary" disabled>✔ Day Completed</button>
    @endif

    </div>
    <div class="row mb-5">

        <div class="col-md-6">
            <div class="dashboard-card projects-card">
                <div class="card-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>

                <div>
                    <div class="card-title">Total Projects Assigned</div>
                    <div class="card-number">{{ $totalProjects }}</div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="dashboard-card tasks-card">
                <div class="card-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div>
                    <div class="card-title">Total Tasks Assigned</div>
                    <div class="card-number">{{ $totalTasks }}</div>
                </div>
            </div>
        </div>



    </div>

    <h3 class="mt-4">My Assigned Tasks</h3>

    <div class="card p-3">

        @if($tasks->count() > 0)

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Task</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th width="320">Work Update</th>
                </tr>
            </thead>

            <tbody>

                @foreach($tasks as $task)

                <tr>

                    <td>{{ $task->name }}</td>

                    <td>{{ $task->project->name ?? '-' }}</td>

                    <td>
                        <span class="badge bg-secondary">
                            {{ $task->status }}
                        </span>
                    </td>

                    <td>

                        @if($task->status == 'Pending')

                        <form action="{{ route('employee.tasks.start',$task->id) }}" method="POST">
                            @csrf

                            <textarea name="comment"
                                class="form-control mb-2"
                                placeholder="Write update for manager..."></textarea>

                            <button class="btn btn-primary btn-sm">
                                Start Working
                            </button>

                        </form>


                        @elseif($task->status == 'In Progress')

                        <form action="{{ route('employee.tasks.complete',$task->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <button class="btn btn-success btn-sm">
                                Mark as Done
                            </button>

                        </form>


                        @else

                        <span class="badge bg-success">
                            Completed
                        </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        @else

        <p>No tasks assigned</p>

        @endif

    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let btn = document.getElementById('checkin-btn');

            if (btn) {
                btn.addEventListener('click', function() {

                    // 🔥 INSTANT UI CHANGE (IMPORTANT)
                    btn.disabled = true;
                    btn.innerText = "✔ Checking...";

                    fetch("{{ route('employee.checkin') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            btn.innerText = "✔ Checked In";

                            // optional: remove alert (bad UX)
                            // alert(data.message);

                            location.reload();
                        })
                        .catch(() => {
                            // ❌ if error → re-enable
                            btn.disabled = false;
                            btn.innerText = "✔ Check In";
                        });

                });
            }

            let checkoutBtn = document.getElementById('checkout-btn');

            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function() {

                    // 🔥 instant UI feedback
                    checkoutBtn.disabled = true;
                    checkoutBtn.innerText = "⏳ Checking out...";

                    fetch("{{ route('employee.checkout') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            checkoutBtn.innerText = "✔ Checked Out";
                            location.reload(); // refresh dashboard
                        })
                        .catch(() => {
                            checkoutBtn.disabled = false;
                            checkoutBtn.innerText = "⏹ Check Out";
                        });

                });
            }


        });
    </script>
    @endsection