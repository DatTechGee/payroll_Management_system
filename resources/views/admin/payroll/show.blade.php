@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <h2 class="text-2xl font-bold text-white">Payroll Details</h2>
                    <span class="px-3 py-1 text-sm rounded-full bg-indigo-500 text-white">{{ $payroll->payroll_id }}</span>
                </div>
                <div class="space-x-4">
                    <a href="{{ route('admin.payroll.edit', $payroll) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit Payroll
                    </a>
                    <a href="{{ route('admin.payroll.payslip', $payroll) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Download Payslip
                    </a>
                    <a href="{{ route('admin.payroll.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Payroll List</a>
                </div>
            </div>
        </div>

        <!-- Employee Information -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="h-6 w-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Employee Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-indigo-600">Employee ID</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->employee_id }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-indigo-600">Full Name</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-indigo-600">Department</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->department->name }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-indigo-600">Position</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->position }}</p>
                </div>
            </div>
        </div>

        <!-- Payroll Information -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Payroll Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Pay Month</p>
                    <p class="text-base font-medium">{{ \Carbon\Carbon::parse($payroll->pay_month)->format('F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date Processed</p>
                    <p class="text-base font-medium">{{ $payroll->date_processed->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Earnings -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="h-6 w-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Earnings</h3>
            </div>
            <div class="bg-white rounded-lg shadow-sm divide-y divide-gray-200">
                <div class="flex justify-between items-center p-4">
                    <span class="text-sm font-medium text-gray-600">Basic Salary</span>
                    <span class="text-lg font-semibold text-gray-900">₦{{ number_format($payroll->basic_salary, 2) }}</span>
                </div>
                @if($payroll->allowance)
                <div class="flex justify-between">
                    <span class="text-gray-600">Allowance</span>
                    <span class="font-medium">₦{{ number_format($payroll->allowance, 2) }}</span>
                </div>
                @endif
                @if($payroll->overtime_hours > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Overtime ({{ $payroll->overtime_hours }} hours)</span>
                    <span class="font-medium">₦{{ number_format($payroll->overtime_pay, 2) }}</span>
                </div>
                @endif
                @if($payroll->bonus)
                <div class="flex justify-between">
                    <span class="text-gray-600">Bonus</span>
                    <span class="font-medium text-green-600">₦{{ number_format($payroll->bonus, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-3 border-t">
                    <span class="font-semibold">Gross Pay</span>
                    <span class="font-semibold">₦{{ number_format($payroll->gross_pay, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Deductions -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 20V4" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Deductions</h3>
            </div>
            @if($payroll->deductions->isNotEmpty())
                @php $deduction = $payroll->deductions->first(); @endphp
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Percentage Based Deductions -->
                    <div class="divide-y divide-gray-200">
                        <div class="p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Tax</span>
                                </div>
                                <span class="text-lg font-semibold text-red-600">₦{{ number_format($deduction->tax, 2) }}</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Pension</span>
                                </div>
                                <span class="text-lg font-semibold text-red-600">₦{{ number_format($deduction->pension, 2) }}</span>
                            </div>
                        </div>
                        
                        @if($deduction->other_deductions > 0)
                            <div class="p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Other Deductions</span>
                                        @if($deduction->other_deductions_description)
                                            <p class="text-xs text-gray-500">{{ $deduction->other_deductions_description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-lg font-semibold text-red-600">₦{{ number_format($deduction->other_deductions, 2) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Total Deductions Summary -->
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-900">Total Deductions</span>
                                <span class="text-lg font-bold text-red-600">₦{{ number_format($deduction->total_deduction, 2) }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                ({{ number_format(($deduction->total_deduction / $payroll->gross_pay) * 100, 1) }}% of gross pay)
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-4">
                    No deductions recorded for this payroll.
                </div>
            @endif
        </div>

        <!-- Net Pay -->
        <div class="p-6">
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold">Net Pay</span>
                <span class="text-xl font-bold text-indigo-600">₦{{ number_format($payroll->net_pay, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection