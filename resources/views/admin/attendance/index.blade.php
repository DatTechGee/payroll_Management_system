@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Attendance Management</h1>
            <p class="mt-1 text-sm text-gray-600">Track and manage employee attendance records</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.location.href='{{ route('admin.attendance.record') }}'" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Record Attendance
            </button>
            <button onclick="exportAttendance()" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Data
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4">
            <form action="{{ route('admin.attendance') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" 
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                           value="{{ request('date', date('Y-m-d')) }}">
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                    <select name="department" id="department" 
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" 
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                        <option value="overtime" {{ request('status') == 'overtime' ? 'selected' : '' }}>Overtime</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime Hours</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances ?? [] as $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $attendance->employee->full_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $attendance->employee->employee_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $attendance->employee->department->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $attendance->date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $attendance->status === 'leave' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $attendance->status === 'overtime' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $attendance->overtime_hours ?? '0' }} hours
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="action-button-edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.attendance.destroy', $attendance->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button-delete">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No attendance records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($attendances) && $attendances->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>
</div>
    <script>
        function exportAttendance() {
            // Get current filter values
            const date = document.getElementById('date').value;
            const department = document.getElementById('department').value;
            const status = document.getElementById('status').value;

            // Construct the export URL with current filters
            let exportUrl = '{{ route('admin.attendance.export') }}?';
            if (date) exportUrl += `date=${date}&`;
            if (department) exportUrl += `department=${department}&`;
            if (status) exportUrl += `status=${status}`;

            // Trigger download
            window.location.href = exportUrl;
        }
    </script>
@endsection