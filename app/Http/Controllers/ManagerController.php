<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Tasks;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Attendance;


class ManagerController extends Controller
{
    public function dashboard()
    {
        $totalProjects = Project::count();
        $totalTasks = Tasks::count();
        $totalEmployees = User::where('role', 'employee')->count();
        $notifications = Notification::where('is_cleared', false)
            ->latest()
            ->get();
        $unreadCount = Notification::where('is_read', false)
            ->where('is_cleared', false)
            ->count();

        $recentProjects = Project::latest()->take(5)->get();

        $recentTasks = Tasks::with('employee', 'project')
            ->latest()
            ->take(5)
            ->get();

        $workingTasks = Tasks::where('status', 'In Progress')
            ->whereDate('updated_at', Carbon::today())
            ->with(['employee', 'project'])
            ->latest()
            ->get();

        $attendances = Attendance::with('user')
            ->whereDate('date', now())
            ->get();

        $hour = date('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 17) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }

        return view('manager.dashboard', compact(
            'totalProjects',
            'totalTasks',
            'totalEmployees',
            'recentProjects',
            'recentTasks',
            'greeting',
            'workingTasks',
            'notifications',
            'unreadCount',
            'attendances'

        ));
    }



    public function employees(Request $request)
    {
        $search = $request->search;

        $employees = User::where('role', 'employee')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->get();

        return view('manager.employees', compact('employees'));
    }



    public function editEmployee($id)
    {
        $employee = User::findOrFail($id);

        return view('register', compact('employee'));
    }

    public function updateEmployee(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,

            // 🔥 NEW FIELDS
            'mobile' => 'required|digits:10',
            'secondary_mobile' => 'nullable|digits:10',
            'dob' => 'nullable|date|before:today',
        ]);

        try {

            $employee = User::findOrFail($id);

            $employee->update([
                'name' => $request->name,
                'email' => $request->email,

                // 🔥 ADD THESE
                'mobile' => $request->mobile,
                'secondary_mobile' => $request->secondary_mobile,
                'dob' => $request->dob,
            ]);

            return redirect()->route('manager.employees')
                ->with('success', 'Employee updated successfully');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the employee.');
        }
    }


    public function deleteEmployee($id)
    {
        try {
            $employee = User::findOrFail($id);

            $employee->delete();

            return redirect()->route('manager.employees')
                ->with('success', 'Employee deleted successfully');
        } catch (QueryException $e) {

            return redirect()->route('manager.employees')
                ->with('error', 'Unable to delete employee. It may be linked to other records.');
        } catch (\Exception $e) {

            return redirect()->route('manager.employees')
                ->with('error', 'Something went wrong while deleting the employee.');
        }
    }

    // Show all projects
    public function projects(Request $request)
    {
        $search = $request->search;

        $projects = Project::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->get();

        return view('manager.projects', compact('projects'));
    }

    // Show create project form
    public function createProject(Request $request)
    {
        $editProject = null;

        if ($request->has('edit')) {
            $editProject = Project::find($request->edit);
        }

        return view('manager.create_project', compact('editProject'));
    }


    public function storeProject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {

            Project::create([
                'name' => ucwords(strtolower($request->name))
            ]);

            return redirect()->route('manager.projects')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong.');
        }
    }

    public function updateProject(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try {

            $project = Project::findOrFail($id);

            $project->update([
                'name' => $request->name
            ]);

            return redirect()->route('manager.projects')
                ->with('success', 'Project updated successfully');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the project.');
        }
    }

    public function showProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $employees = User::where('role', 'employee')->get();

        $editTask = null;

        if ($request->editTask) {
            $editTask = Tasks::find($request->editTask);
        }

        return view(
            'manager.project_show',
            compact('project', 'employees', 'editTask')
        );
    }

    public function deleteProject($id)
    {
        try {

            $project = Project::findOrFail($id);

            Tasks::where('project_id', $project->id)->delete();

            $project->delete();

            return redirect()->route('manager.projects')
                ->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {

            return back()->with('error', 'Something went wrong while deleting the project.');
        }
    }


    public function storeTask(Request $request, $projectId)
    {
        $request->validate([
            'name' => 'required',
            'deadline' => 'nullable|date',
            'employee_id' => 'required',
            'description' => 'required|string'
        ]);

        try {

            Tasks::create([
                'name' => $request->name,
                'deadline' => $request->deadline,
                'project_id' => $projectId,
                'employee_id' => $request->employee_id,
                'description' => $request->description,
                'created_by' => 'Manager'

            ]);

            return redirect()
                ->route('manager.projects.show', $projectId)
                ->with('success', 'Task created successfully');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the task.');
        }
    }

    public function deleteTask($id)
    {
        try {

            $task = Tasks::findOrFail($id);

            $projectId = $task->project_id;

            $task->delete();

            return redirect()
                ->route('manager.projects.show', $projectId)
                ->with('success', 'Task deleted successfully');
        } catch (\Exception $e) {

            return back()->with('error', 'Something went wrong while deleting task');
        }
    }

    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'deadline' => 'nullable|date',
            'employee_id' => 'required',
            'description' => 'required'

        ]);

        try {

            $task = Tasks::findOrFail($id);

            $task->update([
                'name' => $request->name,
                'deadline' => $request->deadline,
                'employee_id' => $request->employee_id,
                'description' => $request->description
            ]);

            return redirect()->route('manager.projects.show', $task->project_id)
                ->with('success', 'Task updated successfully');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the task.');
        }
    }

    public function showEmployee($id)
    {
        $employee = User::findOrFail($id);

        return view('manager.employee_show', compact('employee'));
    }

    public function employeeTasks($id)
    {
        $employee = User::findOrFail($id);

        $tasks = Tasks::where('employee_id', $id)
            ->with('project')
            ->get();

        return view('manager.employee_tasks', compact('employee', 'tasks'));
    }

    public function searchEmployees(Request $request)
    {
        $search = $request->search;

        $employees = User::where('role', 'employee')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->get();

        return response()->json($employees);
    }

    public function markRead()
    {

        Notification::where('is_read', false)->update([
            'is_read' => true
        ]);

        return response()->json(['success' => true]);;
    }

    public function clearNotifications()
    {

        Notification::where('is_cleared', false)->update([
            'is_cleared' => true
        ]);

        return back();
    }

    public function last7Days()
    {
        $notifications = Notification::whereRaw(
            "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
        )->latest()->get();

        return view('manager.notifications_history', compact('notifications'));
    }

    public function allTasks(Request $request)
    {
        $status = $request->status ?? 'all';

        // ✅ STEP 1: Always get latest 10 first
        $latestTasks = Tasks::with(['project', 'employee'])
            ->latest()
            ->take(10)
            ->get();

        // ✅ STEP 2: Filter ONLY from these 10
        if ($status == 'all') {
            $tasks = $latestTasks;
        } else {
            $tasks = $latestTasks->where('status', $status);
        }

        // ✅ STEP 3: Counts ALSO from same 10
        $totalTasks = $latestTasks->count();
        $pendingTasks = $latestTasks->where('status', 'Pending')->count();
        $inProgressTasks = $latestTasks->where('status', 'In Progress')->count();
        $completedTasks = $latestTasks->where('status', 'Completed')->count();

        return view('manager.tasks', compact(
            'tasks',
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks'
        ));
    }


    public function todayAttendance()
    {
        $attendances = Attendance::whereDate('date', now())
            ->get()
            ->keyBy('user_id');

        $employees = User::all();

        // Split into groups
        $presentEmployees = $employees->filter(function ($emp) use ($attendances) {
            return isset($attendances[$emp->id]);
        });

        $absentEmployees = $employees->filter(function ($emp) use ($attendances) {
            return !isset($attendances[$emp->id]);
        });

        return view('manager.attendance', compact(
            'presentEmployees',
            'absentEmployees',
            'attendances'
        ));
    }


    public function attendance()
    {
        $query = Attendance::with('user')
            ->whereDate('date', now());

        if (request('search')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }

        if (request('status') == 'late') {
            $query->whereTime('check_in_time', '>', '12:00:00');
        }

        if (request('status') == 'on_time') {
            $query->whereTime('check_in_time', '<=', '12:00:00');
        }

        $attendances = $query->get()->keyBy('user_id');

        $employees = User::all();

        // Split into groups
        $presentEmployees = $employees->filter(function ($emp) use ($attendances) {
            return isset($attendances[$emp->id]);
        });

        $absentEmployees = $employees->filter(function ($emp) use ($attendances) {
            return !isset($attendances[$emp->id]);
        });

        return view('manager.attendance', compact(
            'presentEmployees',
            'absentEmployees',
            'attendances'
        ));
    }
}
