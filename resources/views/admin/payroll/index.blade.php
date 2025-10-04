@extends('layouts.app')

@section('content')
<div class="w-full mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Payroll Management</h2>
        <a href="{{ route('admin.payroll.process') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Process New Payroll
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4">
            <form action="{{ route('admin.payroll.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Pay Month</label>
                    <input type="month" name="month" id="month" 
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                           value="{{ request('month', date('Y-m')) }}">
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
                <div class="flex items-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 gap-2 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414v6.586a1 1 0 01-1.414.914l-3-1.5a1 1 0 01-.586-.914v-5.086l-6.414-6.414a1 1 0 01-.293-.707v-2.586z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-600">Total Payroll</dt>
                        <dd class="text-lg font-bold text-gray-900">₦{{ number_format($totalPayroll ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-600">Total Employees</dt>
                        <dd class="text-lg font-bold text-gray-900">{{ $totalEmployees ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 4v16m8-8l-4 4m0-8l4 4" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-600">Total Deductions</dt>
                        <dd class="text-lg font-bold text-gray-900">₦{{ number_format($totalDeductions ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-600">Total Bonuses</dt>
                        <dd class="text-lg font-bold text-gray-900">₦{{ number_format($totalBonuses ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white rounded-lg shadow w-full overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                        <th scope="col" class="w-[18%] px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="w-[10%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pay Month</th>
                        <th scope="col" class="w-[12%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                        <th scope="col" class="w-[12%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allowance</th>
                        <th scope="col" class="w-[12%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bonus</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payrolls ?? [] as $payroll)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ optional($payroll->employee)->first_name }} {{ optional($payroll->employee)->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ optional($payroll->employee)->employee_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $payroll->pay_month }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₦{{ number_format(floatval($payroll->basic_salary), 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₦{{ number_format(floatval($payroll->allowance), 2) }}</div>
                            @if($payroll->employee && $payroll->allowance != $payroll->employee->allowance)
                            <div class="text-xs text-gray-500">
                                (Standard: ₦{{ number_format($payroll->employee->allowance, 2) }})
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₦{{ number_format(floatval($payroll->bonus), 2) }}</div>
                            @if($payroll->bonus > 0)
                            <div class="text-xs text-gray-500">
                                @if(request('bonus_type') === 'percentage')
                                    ({{ number_format((floatval($payroll->bonus) / floatval($payroll->basic_salary)) * 100, 1) }}% of basic)
                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                ₦{{ number_format($payroll->deductions->sum('total_deduction'), 2) }}
                                @if($payroll->deductions->isNotEmpty())
                                <div class="text-xs text-gray-500">
                                    (Tax: {{ $payroll->deductions->first()->getTaxRatePercentage() }}%,
                                    Pension: {{ $payroll->deductions->first()->getPensionRatePercentage() }}%
                                    @if($payroll->other_deductions > 0)
                                        @if(request('deduction_type') === 'percentage')
                                        , Other: {{ number_format((floatval($payroll->other_deductions) / floatval($payroll->basic_salary)) * 100, 1) }}%
                                        @endif
                                    @endif
                                    )
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₦{{ number_format(floatval($payroll->net_pay), 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.payroll.show', $payroll->id) }}" class="action-button-view">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('admin.payroll.edit', $payroll->id) }}" class="action-button-edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.payroll.destroy', $payroll->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button-delete" onclick="return confirm('Are you sure you want to delete this payroll?')">
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
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No payroll records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($payrolls) && $payrolls->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $payrolls->links() }}
        </div>
        @endif
    </div>
</div>
@endsection