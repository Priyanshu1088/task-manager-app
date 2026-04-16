<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceController extends Controller
{
    public function attendanceHistory()
    {
        $employees = User::all();

        $attendance = collect();
        $presentDays = null;
        $totalWorkingDays = null;

        if (request('employee_id') && request('month')) {

            $month = Carbon::parse(request('month'));

            // ✅ Get attendance
            $attendance = Attendance::with('user')
                ->where('user_id', request('employee_id'))
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->latest()
                ->paginate(10);

            // ✅ Present days
            $presentDays = $attendance->total(); // IMPORTANT (not count())

            // ✅ Total working days (Mon–Fri only)
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $period = CarbonPeriod::create($start, $end);

            $totalWorkingDays = 0;

            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    $totalWorkingDays++;
                }
            }
        }

        return view('manager.attendance.history', compact(
            'employees',
            'attendance',
            'presentDays',
            'totalWorkingDays'
        ));
    }
}
