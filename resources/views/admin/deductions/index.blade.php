@extends('layouts.app')

@section('content')
<div class="w-full mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Deductions Management</h2>
            <a href="{{ route('admin.deductions.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 gap-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Deduction
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
            <form action="{{ route('admin.deductions.index') }}" method="GET" class="flex items-center gap-4">
                <div class="flex-1">
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Filter by Month</label>
                    <input type="month" 
                           id="month" 
                           name="month" 
                           value="{{ request('month', now()->format('Y-m')) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex-1">
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select id="department" 
                            name="department" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="h-10 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

       

    <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500">Total Deductions This Month</h3>
                <p class="text-2xl font-bold text-gray-900">₦{{ number_format($totalDeductions ?? 0, 2) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500">Average Tax Rate</h3>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($averageTaxRate ?? 0, 1) }}%</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500">Employees With Deductions</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $employeesCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow w-full overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-[14%] px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Month
                    </th>
                    <th class="w-[18%] px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Employee
                    </th>
                    <th class="w-[14%] px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Department
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tax
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pension
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Other Deductions
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($deductions as $deduction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $deduction->payroll->pay_month->format('F Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Created {{ $deduction->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $deduction->payroll->employee->first_name }} {{ $deduction->payroll->employee->last_name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $deduction->payroll->employee->employee_id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $deduction->payroll->employee->department->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ number_format($deduction->tax_rate ?? (($deduction->tax / $deduction->payroll->gross_pay) * 100), 1) }}%
                            </div>
                            <div class="text-xs text-gray-500">
                                Amount: ₦{{ number_format($deduction->tax, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ number_format($deduction->pension_rate ?? (($deduction->pension / $deduction->payroll->gross_pay) * 100), 1) }}%
                            </div>
                            <div class="text-xs text-gray-500">
                                Amount: ₦{{ number_format($deduction->pension ?? 0, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($deduction->other_deductions)
                                <span title="{{ $deduction->other_deductions_description }}">
                                    ₦{{ number_format($deduction->other_deductions, 2) }}
                                    @if($deduction->other_deductions_description)
                                        <small class="text-gray-500">({{ Str::limit($deduction->other_deductions_description, 20) }})</small>
                                    @endif
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ₦{{ number_format($deduction->total_deduction, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.deductions.edit', $deduction) }}" class="action-button-edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.deductions.destroy', $deduction) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this deduction?');"
                                      class="inline">
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
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $deductions->links() }}
    </div>
</div>
@endsection