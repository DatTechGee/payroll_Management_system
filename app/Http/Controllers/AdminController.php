<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Deduction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get total employees
        $totalEmployees = Employee::count();

        // Get present employees today
        $presentToday = Attendance::whereDate('date', Carbon::today())
            ->where('status', 'present')
            ->count();

        // Get employees on leave today
        $onLeave = Attendance::whereDate('date', Carbon::today())
            ->where('status', 'leave')
            ->count();

        // Get total payroll for current month
        $totalPayroll = Payroll::whereMonth('date_processed', Carbon::now()->month)
            ->sum('gross_pay');

        // Get total deductions for current month
        $totalDeductions = Deduction::whereHas('payroll', function($query) {
            $query->whereMonth('pay_month', Carbon::now()->format('Y-m'));
        })->sum('total_deduction');

        // Get recent activities (last 5)
        $recentActivities = Payroll::with('employee')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($payroll) {
                return (object)[
                    'description' => "Processed payroll for {$payroll->employee->full_name}",
                    'created_at' => $payroll->date_processed
                ];
            });

        return view('admin.dashboard', compact(
            'totalEmployees',
            'presentToday',
            'onLeave',
            'totalPayroll',
            'totalDeductions',
            'recentActivities'
        ));
    }
}