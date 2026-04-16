@extends('manager.layout') {{-- or your manager layout --}}

@section('content')

<div class="container">
    <h3 class="mb-4">Last 7 Days Notifications</h3>

    @forelse($notifications as $note)
        <div class="card mb-2 p-3 {{ $note->is_read ? '' : 'bg-light fw-bold' }}">
            {{ $note->message }}

            <div class="text-muted small mt-1">
                {{ $note->created_at->diffForHumans() }}
            </div>
        </div>
    @empty
        <div class="text-muted">No notifications in last 7 days</div>
    @endforelse
</div>

@endsection