<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export employees data to CSV
     */
    public function exportEmployees(Request $request)
    {
        // Get filtered employees
        $query = Employee::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('employee_id', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('department', function($q) use ($request) {
                $q->where('name', $request->department);
            });
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $employees = $query->get();

        // Create CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees_' . date('Y-m-d') . '.csv"',
        ];

        $handle = fopen('php://temp', 'w+');
        
        // Add headers
        fputcsv($handle, [
            'Employee ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Department',
            'Position',
            'Date Hired',
            'Basic Salary',
            'Allowance',
        ]);

        // Add data rows
        foreach ($employees as $employee) {
            fputcsv($handle, [
                $employee->employee_id,
                $employee->first_name,
                $employee->last_name,
                $employee->email,
                $employee->phone,
                $employee->department,
                $employee->position,
                $employee->date_hired->format('Y-m-d'),
                $employee->basic_salary,
                $employee->allowance,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return Response::make($content, 200, $headers);
    }

    /**
     * Export attendance data to CSV
     */
    public function exportAttendance(Request $request)
    {
        // Get filtered attendance records
        $query = Attendance::with('employee');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->get();

        // Create CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance_' . date('Y-m-d') . '.csv"',
        ];

        $handle = fopen('php://temp', 'w+');
        
        // Add headers
        fputcsv($handle, [
            'Date',
            'Employee ID',
            'Employee Name',
            'Department',
            'Status',
            'Check In',
            'Check Out',
            'Overtime Hours',
            'Notes'
        ]);

        // Add data rows
        foreach ($attendances as $attendance) {
            fputcsv($handle, [
                $attendance->date->format('Y-m-d'),
                $attendance->employee->employee_id,
                $attendance->employee->full_name,
                $attendance->employee->department,
                ucfirst($attendance->status),
                $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-',
                $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-',
                $attendance->overtime_hours ?? '0',
                $attendance->notes ?? ''
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return Response::make($content, 200, $headers);
    }
}