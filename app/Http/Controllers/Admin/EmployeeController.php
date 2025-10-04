<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::where('role', 'employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $employees = $query->paginate(10);
        $departments = \App\Models\Department::orderBy('name')->get();
        $positions = Employee::distinct('position')->pluck('position');

        return view('admin.employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('admin.employees.form', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|unique:employees',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:employees',
            'password' => 'required|min:6|confirmed',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required',
            'date_hired' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bank_name' => 'required',
            'account_number' => 'required'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'employee';

        Employee::create($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('admin.employees.form', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'position' => 'required',
            'date_hired' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bank_name' => 'required',
            'account_number' => 'required'
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:6|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $employee->update($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function show(Employee $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    public function export(Request $request)
    {
        $query = Employee::where('role', 'employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $employees = $query->get();

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
            'Department',
            'Position',
            'Date Hired',
            'Basic Salary',
            'Allowance',
            'Bank Name',
            'Account Number'
        ]);

        // Add data rows
        foreach ($employees as $employee) {
            fputcsv($handle, [
                $employee->employee_id,
                $employee->first_name,
                $employee->last_name,
                $employee->email,
                $employee->department->name,
                $employee->position,
                $employee->date_hired->format('Y-m-d'),
                number_format($employee->basic_salary, 2),
                $employee->allowance ? number_format($employee->allowance, 2) : '0.00',
                $employee->bank_name,
                $employee->account_number
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, $headers);
    }
}