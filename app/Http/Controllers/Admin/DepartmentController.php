<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')
            ->latest()
            ->paginate(10);

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments'],
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.form', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Cannot delete department that has employees.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index');
    }
}