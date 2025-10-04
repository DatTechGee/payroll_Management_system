@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Payslips</h1>

        <!-- Payslip List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Your Payslips</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">View and download your monthly payslips.</p>
            </div>

            <div class="bg-white">
                <div class="divide-y divide-gray-200">
                    @forelse($payslips ?? [] as $payslip)
                    <div class="px-4 py-6 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ $payslip->pay_month }}</h4>
                                <p class="mt-1 text-sm text-gray-500">Processed on {{ $payslip->date_processed->format('M d, Y') }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-lg font-semibold text-gray-900">${{ number_format($payslip->net_pay, 2) }}</span>
                                <a href="{{ route('employee.payslip.download', $payslip->id) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                        
                        <!-- Payslip Details -->
                        <div class="mt-4 border rounded-lg overflow-hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                                <div class="p-4">
                                    <h5 class="text-sm font-medium text-gray-500">Earnings</h5>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Basic Salary</span>
                                            <span class="text-sm font-medium text-gray-900">${{ number_format($payslip->basic_salary, 2) }}</span>
                                        </div>
                                        @if($payslip->allowance)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Allowance</span>
                                            <span class="text-sm font-medium text-gray-900">${{ number_format($payslip->allowance, 2) }}</span>
                                        </div>
                                        @endif
                                        @if($payslip->bonus)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Bonus</span>
                                            <span class="text-sm font-medium text-gray-900">${{ number_format($payslip->bonus, 2) }}</span>
                                        </div>
                                        @endif
                                        <div class="pt-2 border-t">
                                            <div class="flex justify-between font-medium">
                                                <span class="text-sm text-gray-600">Total Earnings</span>
                                                <span class="text-sm text-gray-900">${{ number_format($payslip->basic_salary + ($payslip->allowance ?? 0) + ($payslip->bonus ?? 0), 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h5 class="text-sm font-medium text-gray-500">Deductions</h5>
                                    <div class="mt-2 space-y-2">
                                        @foreach($payslip->deductions as $deduction)
                                            @if($deduction->tax > 0)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Tax</span>
                                                <span class="text-sm font-medium text-gray-900">-₦{{ number_format($deduction->tax, 2) }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($deduction->pension > 0)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Pension</span>
                                                <span class="text-sm font-medium text-gray-900">-₦{{ number_format($deduction->pension, 2) }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($deduction->other_deductions > 0)
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Other Deductions</span>
                                                <span class="text-sm font-medium text-gray-900">-₦{{ number_format($deduction->other_deductions, 2) }}</span>
                                            </div>
                                            @if($deduction->other_deductions_description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $deduction->other_deductions_description }}</p>
                                            @endif
                                            @endif
                                        @endforeach
                                        
                                        <div class="pt-2 border-t">
                                            <div class="flex justify-between font-medium">
                                                <span class="text-sm text-gray-600">Total Deductions</span>
                                                <span class="text-sm text-gray-900">-₦{{ number_format($payslip->deductions->sum('total_deduction'), 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-gray-900">Net Pay</span>
                                    <span class="text-gray-900">${{ number_format($payslip->net_pay, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-6 sm:px-6 text-center text-gray-500">
                        No payslips available yet.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection