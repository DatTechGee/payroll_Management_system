@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-white">Edit Payroll</h1>
                    <span class="px-3 py-1 text-sm rounded-full bg-purple-500 text-white">{{ $payroll->payroll_id }}</span>
                </div>
                <a href="{{ route('admin.payroll.show', $payroll) }}" 
                   class="flex items-center text-white hover:text-purple-200 transition-colors duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Details
                </a>
            </div>
        </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Payroll Details for {{ $payroll->employee->full_name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Pay Month: {{ $payroll->pay_month }}
            </p>
        </div>

        <form action="{{ route('admin.payroll.update', $payroll) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Employee Details Summary -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="p-2 bg-purple-100 rounded-full">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $payroll->employee->position }} - {{ $payroll->employee->department->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Fixed Details -->
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <div class="block w-full pl-7 py-2 text-gray-900 font-medium bg-gray-50 rounded-md">
                                {{ number_format($payroll->basic_salary, 2) }}
                            </div>
                        </div>
                    </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Allowance</label>
                            <div class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                ₦{{ number_format($payroll->allowance ?? 0, 2) }}
                            </div>
                        </div>


                    </div>

                    <!-- Editable Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="bonus" class="block text-sm font-medium text-gray-700">Bonus</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" min="0" name="bonus" id="bonus" 
                                       value="{{ old('bonus', $payroll->bonus) }}"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('bonus')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="other_deductions" class="block text-sm font-medium text-gray-700">Other Deductions</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" min="0" name="other_deductions" id="other_deductions" 
                                       value="{{ old('other_deductions', $payroll->other_deductions) }}"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('other_deductions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="other_deductions_description" class="block text-sm font-medium text-gray-700">
                                Other Deductions Description
                            </label>
                            <div class="mt-1">
                                <textarea id="other_deductions_description" name="other_deductions_description" rows="3"
                                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('other_deductions_description', $payroll->deductions->first()->other_deductions_description ?? '') }}</textarea>
                            </div>
                            @error('other_deductions_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Payroll
                </button>
            </div>
        </form>
    </div>
</div>
@endsection