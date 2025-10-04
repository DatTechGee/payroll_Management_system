@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white">Process New Payroll</h2>
                <a href="{{ route('admin.payroll.index') }}" class="flex items-center text-white hover:text-blue-200 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.payroll.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700">Select Employee</label>
                        <div class="mt-1">
                            <select id="employee_id" name="employee_id" required 
                                    class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select an employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            data-salary="{{ $employee->basic_salary }}"
                                            data-allowance="{{ $employee->allowance }}">
                                        {{ $employee->employee_id }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="pay_month" class="block text-sm font-medium text-gray-700">Pay Month</label>
                        <div class="mt-1">
                            <input type="month" id="pay_month" name="pay_month" required
                                   class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ now()->format('Y-m') }}">
                        </div>
                    </div>
                </div>

                <!-- Salary Information (Read-only) -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Salary Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Basic Salary</label>
                            <div id="basic_salary_display" class="mt-1 p-2 bg-white border border-gray-300 rounded-md text-gray-700">
                                ₦0.00
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Standard Allowance</label>
                            <div id="allowance_display" class="mt-1 p-2 bg-white border border-gray-300 rounded-md text-gray-700">
                                ₦0.00
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Compensation -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Compensation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="allowance" class="block text-sm font-medium text-gray-700">Additional Allowance</label>
                            <div class="mt-1">
                                <input type="number" id="allowance" name="allowance" step="0.01" min="0"
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="0.00">
                                <p class="mt-1 text-xs text-gray-500">Enter any additional allowance amount</p>
                            </div>
                        </div>
                        <div>
                            <label for="bonus" class="block text-sm font-medium text-gray-700">Performance Bonus</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex gap-2">
                                    <input type="number" id="bonus" name="bonus" step="0.01" min="0"
                                           class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="0.00">
                                    <select name="bonus_type" id="bonus_type"
                                            class="block w-24 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">%</option>
                                    </select>
                                </div>
                                <p class="text-xs text-gray-500">Enter bonus amount (fixed amount or percentage of basic salary)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax and Pension Rates -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tax and Pension Rates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                            <div class="mt-1">
                                <input type="number" 
                                       id="tax_rate" 
                                       name="tax_rate" 
                                       step="0.01" 
                                       min="0" 
                                       max="100"
                                       value="{{ config('payroll.tax_rate', 0.1) * 100 }}"
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Enter tax rate (%)">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to use default rate ({{ config('payroll.tax_rate', 0.1) * 100 }}%)</p>
                            </div>
                        </div>
                        <div>
                            <label for="pension_rate" class="block text-sm font-medium text-gray-700">Pension Rate (%)</label>
                            <div class="mt-1">
                                <input type="number" 
                                       id="pension_rate" 
                                       name="pension_rate" 
                                       step="0.01" 
                                       min="0" 
                                       max="100"
                                       value="{{ config('payroll.pension_rate', 0.05) * 100 }}"
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Enter pension rate (%)">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to use default rate ({{ config('payroll.pension_rate', 0.05) * 100 }}%)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Deductions -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Other Deductions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="other_deductions" class="block text-sm font-medium text-gray-700">Deduction Amount</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex gap-2">
                                    <input type="number" id="other_deductions" name="other_deductions" step="0.01" min="0"
                                           class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="0.00">
                                    <select name="deduction_type" id="deduction_type"
                                            class="block w-24 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">%</option>
                                    </select>
                                </div>
                                <p class="text-xs text-gray-500">Enter deduction amount (fixed amount or percentage of basic salary)</p>
                            </div>
                        </div>
                        <div>
                            <label for="other_deductions_description" class="block text-sm font-medium text-gray-700">Deduction Description</label>
                            <div class="mt-1">
                                <input type="text" id="other_deductions_description" name="other_deductions_description"
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Enter description for the deduction">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <div class="mt-1">
                        <textarea id="notes" name="notes" rows="3" 
                                  class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Enter any additional notes about this payroll"></textarea>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.payroll.index') }}" 
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Process Payroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const basicSalaryDisplay = document.getElementById('basic_salary_display');
    const allowanceDisplay = document.getElementById('allowance_display');

    employeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const salary = parseFloat(selectedOption.dataset.salary) || 0;
        const allowance = parseFloat(selectedOption.dataset.allowance) || 0;

        basicSalaryDisplay.textContent = '₦' + salary.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        allowanceDisplay.textContent = '₦' + allowance.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    });
});
</script>
@endsection