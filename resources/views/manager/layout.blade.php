<!DOCTYPE html>
<html>

<head>
    <title>Manager Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;           
            overflow-x: hidden;

        }

        /* SIDEBAR BASE */
        .sidebar {
            width: 250px;
            background: #0f172a;
            /* 🔥 deep navy */
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
        }

        /* MENU LINKS */
        .sidebar a {
            color: #cbd5e1;
            /* soft gray text */
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            transition: all 0.2s ease;
        }

        /* HOVER */
        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
        }

        /* ACTIVE MENU */
        .active-menu {
            background: #1e293b;
            border-left: 4px solid #3b82f6;
            color: #fff !important;
            font-weight: 600;
        }

        .logout-btn {
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: scale(1.03);
        }

        .active-menu {

            border-left: 4px solid #3b82f6;
            font-weight: 600;
        }



        .btn i {
            font-size: 14px;
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

        .comment-box {
            background: #f8f9fa;
            border-left: 4px solid #3b82f6;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #495057;
            display: inline-block;
            margin-top: 4px;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 250px;
            /* sidebar width */
            right: 0;
            height: 60px;
            background: #0f172a;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding: 80px 30px 30px;
            /* 🔥 space for topbar */
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }

        /* COLLAPSE SIDEBAR */
        .sidebar.collapsed {
            width: 70px;
        }

        /* HEADER */
        .sidebar.collapsed .sidebar-title {
            display: none;
        }

        .sidebar.collapsed .sidebar-header {
            justify-content: center;
        }

        .sidebar.collapsed #toggleSidebar {
            margin: 0 auto;
        }

        /* MENU */
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar a i {
            font-size: 18px;
            min-width: 20px;
        }

        /* HIDE TEXT */
        .sidebar.collapsed a span {
            display: none;
        }

        /* CENTER ICONS */
        .sidebar.collapsed a {
            justify-content: center;
        }

        /* CONTENT SHIFT */
        .sidebar.collapsed~.main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* TOPBAR FIX */
       .topbar {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 60px;
            background: #0f172a;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            padding: 80px 30px 30px;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }

        /* COLLAPSED STATE */
        .sidebar.collapsed ~ .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* 🔥 FIX TOPBAR ALSO */
        .sidebar.collapsed ~ .main-content .topbar {
            left: 70px;
        }

        /* LOGOUT */
        .logout-btn {
            width: 100%;
        }

        .sidebar.collapsed .logout-btn {
            width: 50px;
            height: 50px;
            padding: 0;
        }

        .sidebar.collapsed .logout-btn span {
            display: none;
        }

        .sidebar.collapsed ~ .main-content .topbar {
            left: 70px;
        }

        .sidebar ~ .main-content .topbar {
            left: 250px;
        }

    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">

            <!-- HEADER -->
            <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3">
                <h5 class="text-white m-0 sidebar-title">Manager</h5>

                <button id="toggleSidebar" class="btn btn-sm btn-light">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <!-- MENU -->
            <a href="{{ route('manager.dashboard') }}"
                class="{{ request()->routeIs('manager.dashboard') ? 'active-menu' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('manager.projects') }}"
                class="{{ request()->routeIs('manager.projects*') ? 'active-menu' : '' }}">
                <i class="bi bi-folder"></i>
                <span>Projects</span>
            </a>

            <a href="{{ route('manager.employees') }}"
                class="{{ request()->routeIs('manager.employees*') ? 'active-menu' : '' }}">
                <i class="bi bi-people"></i>
                <span>Employees</span>
            </a>

            <!-- LOGOUT -->
            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST" class="px-3 pb-3">
                    @csrf

                    <button type="button" id="logoutBtn"
                        class="btn btn-danger logout-btn w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </div>

        <!-- Content -->
        <div class="main-content">

            <div class="topbar d-flex justify-content-between align-items-center px-4 py-2">
                <!-- LEFT SIDE (MOBILE BUTTON) -->
                

                <!-- RIGHT SIDE -->
                <div class="d-flex align-items-center gap-2 ms-auto">

                    <i class="bi bi-person-circle fs-5 text-white"></i>

                    <span class="text-white fw-semibold">
                        {{ session('user_name') }}
                    </span>

                    <!-- 🔔 NOTIFICATION BUTTON -->
                    <button class="btn text-white p-0 border-0 position-relative"
                        data-bs-toggle="dropdown"
                        id="notifBell">

                        <i class="bi bi-bell fs-5"></i>

                        @if($unreadCount > 0)
                        <span id="notif-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                        @endif

                    </button>


                    <!-- 🔔 DROPDOWN -->
                    <div id="notification-list" class="dropdown-menu dropdown-menu-end shadow p-0" style="width:300px; border-radius:12px; overflow:hidden;">

                        <!-- 🔥 HEADER -->
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                            <span class="fw-semibold">Notifications</span>

                            @if($notifications->count() > 0)
                                <form action="{{ route('notifications.clear') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger px-2 py-1">
                                        Clear
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- 🔔 LIST -->
                        <div style="max-height:250px; overflow-y:auto;">

                            @forelse($notifications as $note)
                            <div class="px-3 py-2 border-bottom small {{ $note->is_read ? '' : 'bg-light fw-semibold' }}"
                                style="transition:0.2s;">

                                {{ $note->message }}

                                <div class="text-muted" style="font-size:11px;">
                                    {{ $note->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-3">
                                No notifications
                            </div>
                            @endforelse

                        </div>

                        <!-- 🔥 FOOTER BUTTON -->
                        <div class="border-top p-2 text-center bg-white">
                            <a href="{{ route('notifications.history') }}"
                                class="btn btn-sm btn-outline-primary w-100">
                                View last 7 days
                            </a>
                        </div>

                    </div>



                </div>

            </div>
            @yield('content')
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Your custom JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const sidebar = document.querySelector('.sidebar');
        const desktopBtn = document.getElementById('toggleSidebar');
        const mobileBtn = document.getElementById('toggleSidebarMobile');
        const notifBell = document.getElementById('notifBell');
        const logoutBtn = document.getElementById('logoutBtn');

        // 🔔 Notifications
        if (notifBell) {
            notifBell.addEventListener('click', function() {

                fetch('/notifications/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                let badge = document.getElementById('notif-badge');
                if (badge) badge.remove();
            });
        }

        // 🚪 Logout
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                });
            });
        }

        // 📱 Sidebar toggle
        if (desktopBtn) {
            desktopBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');

                if (sidebar.classList.contains('collapsed')) {
                    localStorage.setItem("sidebar", "collapsed");
                } else {
                    localStorage.setItem("sidebar", "expanded");
                }
            });
        }

        if (mobileBtn) {
            mobileBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Load saved state (desktop only)
        if (window.innerWidth > 768 && localStorage.getItem("sidebar") === "collapsed") {
            sidebar.classList.add('collapsed');
        }

    });

    // 🧹 Clear notifications
    function clearNotifications() {
        fetch('/notifications/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(() => location.reload())
            .catch(err => console.error(err));
    }
</script>