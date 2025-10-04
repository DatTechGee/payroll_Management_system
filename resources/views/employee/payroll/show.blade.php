@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <h2 class="text-2xl font-bold text-white">Payroll Details</h2>
                    <span class="px-3 py-1 text-sm rounded-full bg-blue-500 text-white">{{ $payroll->payroll_id }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('employee.payroll.download', $payroll) }}" 
                       class="action-button-view">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Payslip
                    </a>
                    <a href="{{ route('employee.payroll.index') }}" 
                       class="inline-flex items-center text-blue-100 hover:text-white gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Payroll List
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Pay Month</p>
                    <p class="text-base font-medium">{{ $payroll->pay_month->format('F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date Processed</p>
                    <p class="text-base font-medium">{{ $payroll->date_processed->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Working Days</p>
                    <p class="text-base font-medium">{{ $payroll->working_days }} days</p>
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
                    <span class="text-lg font-semibold text-gray-900">@naira($payroll->basic_salary)</span>
                </div>
                @if($payroll->allowance)
                <div class="flex justify-between p-4">
                    <span class="text-gray-600">Allowance</span>
                    <span class="font-medium">@naira($payroll->allowance)</span>
                </div>
                @endif
                @if($payroll->overtime_hours > 0)
                <div class="flex justify-between p-4">
                    <span class="text-gray-600">Overtime ({{ $payroll->overtime_hours }} hours)</span>
                    <span class="font-medium">@naira($payroll->overtime_pay)</span>
                </div>
                @endif
                @if($payroll->bonus)
                <div class="flex justify-between p-4">
                    <span class="text-gray-600">Bonus</span>
                    <span class="font-medium text-green-600">@naira($payroll->bonus)</span>
                </div>
                @endif
                <div class="flex justify-between p-4 bg-gray-50">
                    <span class="font-semibold">Gross Pay</span>
                    <span class="font-semibold">@naira($payroll->gross_pay)</span>
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
                    <div class="divide-y divide-gray-200">
                        <div class="p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Tax</span>
                                <span class="text-lg font-semibold text-red-600">@naira($deduction->tax)</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Pension</span>
                                <span class="text-lg font-semibold text-red-600">@naira($deduction->pension)</span>
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
                                <span class="text-lg font-semibold text-red-600">@naira($deduction->other_deductions)</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="p-4 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-900">Total Deductions</span>
                            <span class="text-lg font-bold text-red-600">@naira($deduction->total_deduction)</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-4">No deductions recorded.</div>
            @endif
        </div>

        <!-- Net Pay -->
        <div class="p-6 bg-gray-50">
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-gray-900">Net Pay</span>
                <span class="text-2xl font-bold text-green-600">@naira($payroll->net_pay)</span>
            </div>
        </div>
    </div>
</div>
@endsection