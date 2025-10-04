<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(10);
        $departments = Department::orderBy('name')->pluck('name');

        return view('admin.attendance.index', compact('attendances', 'departments'));
    }

    public function create()
    {
        $employees = Employee::where('role', 'employee')->get();
        return view('admin.attendance.record', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,leave,overtime',
            'overtime_hours' => 'nullable|numeric|min:0'
        ]);

        // Check if attendance already exists for this employee on this date
        $exists = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Attendance already recorded for this employee on this date.');
        }

        Attendance::create($validated);

        return redirect()->route('admin.attendance')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function edit(Attendance $attendance)
    {
        return view('admin.attendance.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,leave,overtime',
            'overtime_hours' => 'nullable|numeric|min:0'
        ]);

        $attendance->update($validated);

        return redirect()->route('admin.attendance')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendance')
            ->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * Export attendance records to CSV
     */
    public function export(Request $request)
    {
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
                $attendance->employee->department->name,
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

        return response($content, 200, $headers);
    }
}