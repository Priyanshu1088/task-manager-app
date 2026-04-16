<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function history(Request $request)
    {
        $employees = User::all();

        $query = Attendance::query();

        if ($request->employee_id) {
            $query->where('user_id', $request->employee_id);
        }

        if ($request->month) {
            $month = \Carbon\Carbon::parse($request->month);

            $query->whereMonth('date', $month->month)
                ->whereYear('date', $month->year);
        }

        $allRecords = (clone $query)->get();

        $presentDays = $allRecords->whereNotNull('check_in_time')->count();

        $attendance = $query
            ->with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        
        return view('manager.attendance.history', compact('employees', 'attendance', 'presentDays'));
    }
}
