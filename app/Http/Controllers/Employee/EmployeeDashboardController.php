<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::user();
        
        // Get attendance summary for current month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $presentDays = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'present')
            ->count();
        
        $leaveDays = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'leave')
            ->count();
        
        $overtimeHours = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'overtime')
            ->sum('overtime_hours');
        
        // Get recent payslips with deductions
        $recentPayslips = Payroll::with('deductions')
            ->where('employee_id', $employee->id)
            ->orderBy('pay_month', 'desc')
            ->take(3)
            ->get();

        // Calculate current month's payroll if exists
        $currentMonthPayroll = Payroll::with('deductions')
            ->where('employee_id', $employee->id)
            ->whereYear('pay_month', now()->year)
            ->whereMonth('pay_month', now()->month)
            ->first();

        return view('employee.dashboard', compact(
            'employee',
            'presentDays',
            'leaveDays',
            'overtimeHours',
            'recentPayslips'
        ));
    }
}