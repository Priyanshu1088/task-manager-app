<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;
use App\Models\Attendance;
use Carbon\Carbon;






class EmployeeController extends Controller
{
    public function dashboard()
{

    $userId = session('user_id');

    // 🔥 AUTO CHECKOUT
    $this->autoCheckoutIfMissed($userId);
    $employeeId = session('user_id');

    $employee = User::find($employeeId);

    $tasks = Tasks::where('employee_id', $employeeId)
        ->where('status', '!=', 'Completed')
        ->get();

    $totalTasks = $tasks->count();
    $totalProjects = $tasks->pluck('project_id')->unique()->count();

    // ✅ ADD THIS (IMPORTANT)
    $checkedIn = Attendance::where('user_id', $employeeId)
        ->whereDate('date', now())
        ->exists();

    $attendance = Attendance::where('user_id', $employeeId)
        ->whereDate('date', now())
        ->first();

    $checkedOut = $attendance && $attendance->check_out_time ? true : false;

    return view('employee.dashboard', compact(
        'tasks',
        'totalTasks',
        'totalProjects',
        'employee',
        'checkedIn',
        'checkedOut' // ✅ MUST BE HERE
    ));
}

  public function projects()
{
    $employeeId = session('user_id');

    // Step 1: Get project IDs from tasks
    $projectIds = Tasks::where('employee_id', $employeeId)
        ->pluck('project_id')
        ->unique();

    // Step 2: Fetch projects with pagination
    $projects = Project::whereIn('id', $projectIds)
        ->paginate(5);

    return view('employee.projects', compact('projects'));
}

  

    
    public function projectTasks(Request $request, $id)
    {
        $employeeId = session('user_id');

        $status = $request->status;

        $query = Tasks::where('project_id', $id)
                ->where('employee_id', $employeeId);

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        $tasks = $query->get();

        $project = Project::findOrFail($id);

        $totalTasks = Tasks::where('project_id',$id)
            ->where('employee_id',$employeeId)
            ->count();

        $pendingTasks = Tasks::where('project_id',$id)
            ->where('employee_id',$employeeId)
            ->where('status','Pending')
            ->count();

        $inProgressTasks = Tasks::where('project_id',$id)
            ->where('employee_id',$employeeId)
            ->where('status','In Progress')
            ->count();

        $completedTasks = Tasks::where('project_id',$id)
            ->where('employee_id',$employeeId)
            ->where('status','Completed')
            ->count();

        return view('employee.project_tasks', compact(
            'tasks',
            'project',
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks'
        ));
    }


    public function updateStatus(Request $request,$id)
    {

        $request->validate([
            'status' => 'required'
        ]);

        $task = Tasks::findOrFail($id);

        $task->status = $request->status;

        $task->save();

        return back();

    }

    public function startTask(Request $request,$id)
    {
        $task = Tasks::findOrFail($id);

        $task->status = 'In Progress';
        $task->comment = $request->comment;

        $task->save();

        return back()->with('started','Task started successfully');
    }

    public function updateTask(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);

        $task->status = $request->status;

        if ($request->status == 'Completed') {
            $task->completed_at = now();
        }

        $task->save();

        return back()->with('statusUpdated','Task updated successfully');
    }

    public function completeTask($id)
    {
        $task = Tasks::findOrFail($id);

        $task->status = 'Completed';
        $task->completed_at = now();

        $task->save();

        return back()->with('success','Task marked as completed!');
    }

    public function createTask($projectId)
    {
        $project = Project::findOrFail($projectId);

        return view('employee.create_task', compact('project'));
    }

    public function storeTask(Request $request, $projectId)
    {
        $request->validate([
            'name' => 'required',
            'deadline' => 'required|date',
            'description' => 'nullable|string|max:1000'
             
           
        ]);

        $employee = User::findOrFail(session('user_id'));
        $project = Project::findOrFail($projectId);
        
        Notification::create([
            'message' => $employee->name . ' created "' . $request->name . '" in "' . $project->name . '"',
            'is_read' => false,
            'is_cleared' => false
        ]);

        Tasks::create([
            'name' => $request->name,
            'deadline' => $request->deadline,
            'project_id' => $projectId,
            'employee_id' => session('user_id'), // 🔥 important
            'status' => 'Pending',
            'description' => $request->description,
            'created_by' => 'Employee'
        ]);

        return redirect()
            ->route('employee.project.tasks', $projectId)
            ->with('success', 'Task created successfully');
    }

    
    public function checkIn()
    {
        $userId = session('user_id');

        $already = Attendance::where('user_id', $userId)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($already) {
            return response()->json(['message' => 'Already checked in']);
        }

        Attendance::create([
            'user_id' => $userId,
            'date' => Carbon::today(),
            'check_in_time' => Carbon::now()->format('H:i:s')
        ]);

        return response()->json(['message' => 'Checked in successfully']);
    }
    
    public function checkOut()
    {
        $userId = session('user_id');

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', now())
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'You have not checked in yet']);
        }

        if ($attendance->check_out_time) {
            return response()->json(['message' => 'Already checked out']);
        }

        $attendance->update([
            'check_out_time' => now()->format('H:i:s')
        ]);

        return response()->json(['message' => 'Checked out successfully']);
    }

    public function autoCheckoutIfMissed($userId)
    {
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', now())
            ->whereNull('check_out_time')
            ->first();

        if ($attendance && now()->gte(Carbon::parse('18:00'))) {
            $attendance->update([
                'check_out_time' => '19:30:00'
            ]);
        }
    }
 

    
}
