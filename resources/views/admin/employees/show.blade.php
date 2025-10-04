@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Employee Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal and employment details.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.employees.edit', $employee->id) }}" 
                   class="action-button-edit">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Employee
                </a>
                <a href="#" onclick="window.print()" class="action-button-view">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                </a>
                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this employee?');" class="inline">
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
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <!-- Basic Information -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Full name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $employee->employee_id }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $employee->email }}</dd>
                </div>

                <!-- Employment Details -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $employee->department->name }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Position</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $employee->position }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date Hired</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $employee->date_hired ? $employee->date_hired->format('F d, Y') : 'Not set' }}
                    </dd>
                </div>

                <!-- Salary Information -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Basic Salary</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        ₦{{ number_format($employee->basic_salary, 2) }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Allowance</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $employee->allowance ? '₦'.number_format($employee->allowance, 2) : 'No allowance' }}
                    </dd>
                </div>

                <!-- Bank Details -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $employee->bank_name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $employee->account_number }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Current Deductions -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Current Deductions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current month's deductions for this employee.</p>
        </div>
        <div class="border-t border-gray-200">
            @if($employee->latest_deductions)
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tax Deductions -->
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-red-800">Tax</h4>
                                <p class="mt-1 text-lg font-semibold text-red-900">₦{{ number_format($employee->latest_deductions->tax, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pension Deductions -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-blue-800">Pension</h4>
                                <p class="mt-1 text-lg font-semibold text-blue-900">₦{{ number_format($employee->latest_deductions->pension, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Other Deductions -->
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-yellow-800">Other Deductions</h4>
                                <p class="mt-1 text-lg font-semibold text-yellow-900">₦{{ number_format($employee->latest_deductions->other_deductions, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($employee->latest_deductions->other_deductions_description)
                <div class="mt-4 bg-gray-50 rounded p-4">
                    <h4 class="text-sm font-medium text-gray-700">Other Deductions Description:</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $employee->latest_deductions->other_deductions_description }}</p>
                </div>
                @endif

                <!-- Total Deductions -->
                <div class="mt-6 bg-gray-900 text-white rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 4v16m8-8l-4 4m0-8l4 4" />
                            </svg>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-white">Total Deductions</h4>
                                <p class="text-sm text-gray-300">Current Month Total</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-white">
                            ₦{{ number_format($employee->latest_deductions->total_deduction, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
                No deductions have been recorded for the current month.
            </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('admin.employees.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-700 gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Employees List
        </a>
    </div>
</div>
@endsection