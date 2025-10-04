<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get employee count excluding admin
        $totalEmployees = Employee::where('role', 'employee')->count();

        // Get attendance for today
        $today = Carbon::today();
        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->count();

        $onLeave = Attendance::whereDate('date', $today)
            ->where('status', 'leave')
            ->count();

        // Get the current month's start and end dates
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Query payrolls for the current month
        $currentMonthPayrolls = Payroll::with('deductions')
            ->whereYear('pay_month', $startOfMonth->year)
            ->whereMonth('pay_month', $startOfMonth->month)
            ->get();

        // Calculate totals
        $totalPayroll = $currentMonthPayrolls->sum('gross_pay');
        $totalDeductions = $currentMonthPayrolls->flatMap->deductions->sum('total_deduction');
        $totalBonuses = $currentMonthPayrolls->sum('bonus');

        // Get attendance stats
        $currentDate = Carbon::today();
        $totalEmployees = Employee::count();
        
        $presentToday = Attendance::whereDate('date', $currentDate)
            ->where('status', 'present')
            ->count();

        $onLeave = Attendance::whereDate('date', $currentDate)
            ->where('status', 'leave')
            ->count();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'presentToday',
            'onLeave',
            'totalPayroll',
            'totalDeductions',
            'totalBonuses'
        ));
    }
}
