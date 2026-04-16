<!DOCTYPE html>
<html>

<head>
    <title>Employee Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }


        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0f172a;
            /* DARK NAVY */
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        /* LINKS */
        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 14px 18px;
            transition: all 0.2s ease;
        }

        /* HOVER */
        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
        }

        /* ACTIVE */
        .active-menu {
            background: #1e293b;
            border-left: 4px solid #3b82f6;
            color: #fff !important;
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 240px);
            min-height:  calc(100vh - 60px);
        }



        .logout-btn {
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: scale(1.03);
        }

        .dashboard-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 22px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: 0.25s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        /* CARD COLORS */

        .tasks-card {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .projects-card {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        /* ICON */

        .card-icon {
            font-size: 28px;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 14px;
            border-radius: 10px;
        }

        /* TEXT */

        .card-title {
            font-size: 14px;
            opacity: 0.9;
        }

        .card-number {
            font-size: 28px;
            font-weight: 700;
        }

        .task-card {
            padding: 18px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .all-card {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .pending-card {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .progress-card {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .completed-card {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .task-card h6 {
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .task-card h3 {
            font-weight: 700;
        }

        .task-card {
            border-radius: 10px;
            transition: all .25s ease;
            border: 2px solid transparent;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .active-card {
            border: 2px solid #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            transform: translateY(-2px);
        }

        .navy-card {
            background: #0f172a;
            /* navy blue */
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
            justify-content: flex-end;
            padding: 0 20px;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding:80px 30px 30px;
            /* 🔥 space for topbar */
            width: calc(100% - 240px);
        }

        .sidebar.collapsed .sidebar-title {
            display: none;
        }

        /* BUTTON ALIGN CENTER WHEN COLLAPSED */
        .sidebar.collapsed #toggleSidebar {
            margin: 0 auto;
        }

        /* OPTIONAL: MAKE BUTTON CLEAN */
        #toggleSidebar {
            padding: 4px 8px;
        }

        /* 🔥 COLLAPSE SIDEBAR */
        .sidebar.collapsed {
            width: 70px;
        }

        /* HIDE TEXT */
        .sidebar.collapsed a span {
            display: none;
        }

        /* CENTER ICONS */
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar.collapsed a {
            justify-content: center;
        }

        /* ICON STYLE */
        .sidebar a i {
            font-size: 18px;
            min-width: 20px;
        }

        /* SHIFT CONTENT */
        .sidebar.collapsed ~ .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* FIX TOPBAR */
        .sidebar.collapsed ~ .main-content .topbar {
            left: 70px;
        }

        /* NORMAL */
        .logout-btn {
            transition: all 0.3s ease;
        }

        /* COLLAPSED */
        .sidebar.collapsed .logout-btn {
            width: 45px;
            height: 45px;
            padding: 0;   
            display: flex;
            align-items: center;
            justify-content: center;
        }

        
        .sidebar.collapsed .logout-btn span {
            display: none;
        }

        /* MAKE BUTTON SQUARE */
        .sidebar.collapsed .logout-btn {
            width: 50px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* NORMAL BUTTON */
        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* ICON FIX */
        .logout-btn i {
            font-size: 18px;
            color: white;
            display: inline-block;
        }

        /* COLLAPSED MODE */
        .sidebar.collapsed .logout-btn {
            width: 50px;
            height: 50px;
            padding: 0;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* HIDE TEXT */
        .sidebar.collapsed .logout-btn span {
            display: none;
        }

    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body>


    <div>
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">
            <div>
                <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3">

                    <h5 class="text-white m-0 sidebar-title">Employee</h5>

                    <button id="toggleSidebar" class="btn btn-sm btn-light">
                        <i class="bi bi-list"></i>
                    </button>

                </div>
                <a href="{{ route('employee.dashboard') }}"
                    class="{{ request()->routeIs('employee.dashboard') ? 'active-menu' : '' }}">
                    
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('employee.tasks') }}"
                    class="{{ request()->is('employee/tasks*') || request()->is('employee/project*') ? 'active-menu' : '' }}">
                    
                    <i class="bi bi-folder"></i>
                    <span>My Projects</span>
                </a>

            </div>

            <div class="mt-auto">
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="mt-auto px-3 pb-3">
                    @csrf

                    <button type="button" id="logoutBtn" class="btn btn-danger w-100 logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>

                </form>
            </div>
        </div>




        <!-- Content -->
        <div class="main-content">
            <div class="topbar d-flex justify-content-end align-items-center px-4 py-2">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-person-circle fs-5 text-white"></i>

                    <span class="text-white fw-semibold">
                        {{ session('user_name') }}
                    </span>

                </div>

            </div>
            @yield('content')

        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            confirmButtonColor: '#4f46e5'
        });
    </script>
    @endif

</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('logoutBtn')?.addEventListener('click', function() {

        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, logout'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });

    });

   document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');

    // ✅ LOAD SAVED STATE
    if (localStorage.getItem("sidebar") === "collapsed") {
        sidebar.classList.add('collapsed');
    }

    // ✅ TOGGLE + SAVE STATE
    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            localStorage.setItem("sidebar", "collapsed");
        } else {
            localStorage.setItem("sidebar", "expanded");
        }
    });

});
</script>

</html>