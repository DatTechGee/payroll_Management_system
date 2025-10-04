@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Record Attendance</h2>
        <form action="{{ route('admin.attendance.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700">Select Employee</label>
                <select id="employee_id" name="employee_id" required 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select an employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->employee_id }} - {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    value="{{ date('Y-m-d') }}">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" required 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="leave">Leave</option>
                    <option value="overtime">Overtime</option>
                </select>
            </div>
            <div>
                <label for="overtime_hours" class="block text-sm font-medium text-gray-700">Overtime Hours (if applicable)</label>
                <input type="number" id="overtime_hours" name="overtime_hours" step="0.01" min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="0.00">
            </div>
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="flex justify-end">
                <a href="{{ route('admin.attendance') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Record Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection