@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Attendance Record</h2>
        
        <div class="mb-6">
            <p class="text-gray-600">Employee: <span class="font-semibold">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</span></p>
            <p class="text-gray-600">Date: <span class="font-semibold">{{ $attendance->date->format('F d, Y') }}</span></p>
        </div>

        <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="present" {{ $attendance->status === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ $attendance->status === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="leave" {{ $attendance->status === 'leave' ? 'selected' : '' }}>Leave</option>
                    <option value="overtime" {{ $attendance->status === 'overtime' ? 'selected' : '' }}>Overtime</option>
                </select>
            </div>

            <div>
                <label for="overtime_hours" class="block text-sm font-medium text-gray-700">Overtime Hours (if applicable)</label>
                <input type="number" id="overtime_hours" name="overtime_hours" step="0.01" min="0" 
                    value="{{ $attendance->overtime_hours }}"
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
                <a href="{{ route('admin.attendance') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Update Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection