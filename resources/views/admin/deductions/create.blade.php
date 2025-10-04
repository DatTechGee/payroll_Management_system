@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">{{ isset($deduction) ? 'Edit Deduction' : 'Create New Deduction' }}</h2>
                <a href="{{ route('admin.deductions.index') }}" 
                   class="text-indigo-600 hover:text-indigo-900">
                    Back to List
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ isset($deduction) ? route('admin.deductions.update', $deduction) : route('admin.deductions.store') }}" 
                  method="POST" 
                  class="space-y-6">
                @csrf
                @if(isset($deduction))
                    @method('PUT')
                @endif

                <!-- Employee and Month Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!isset($deduction))
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">
                                Employee
                            </label>
                            <select id="employee_id" 
                                    name="employee_id" 
                                    required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            data-salary="{{ $employee->basic_salary }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_id }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="deduction_month" class="block text-sm font-medium text-gray-700">
                                Deduction Month
                            </label>
                            <input type="month" 
                                   id="deduction_month" 
                                   name="deduction_month" 
                                   required 
                                   value="{{ old('deduction_month', now()->format('Y-m')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    @endif
                </div>

                <!-- Employee Salary Information -->
                <div id="salary_info" class="hidden bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Employee Salary Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500">Basic Salary</label>
                            <div id="basic_salary_display" class="text-lg font-semibold text-gray-900">₦0.00</div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Calculated Gross Pay</label>
                            <div id="gross_pay_display" class="text-lg font-semibold text-gray-900">₦0.00</div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="tax_rate" class="form-label">
                        Tax Rate (%)
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" 
                               id="tax_rate" 
                               name="tax_rate" 
                               step="0.01" 
                               min="0"
                               max="100"
                               required 
                               value="{{ old('tax_rate', isset($deduction) ? $deduction->getTaxRatePercentage() : config('payroll.tax_rate', 10) * 100) }}"
                               class="form-input">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">This rate will be applied to the employee's gross pay to calculate tax.</p>
                </div>

                <div>
                    <label for="pension_rate" class="form-label">
                        Pension Rate (%)
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" 
                               id="pension_rate" 
                               name="pension_rate" 
                               step="0.01" 
                               min="0"
                               max="100"
                               required 
                               value="{{ old('pension_rate', isset($deduction) ? $deduction->getPensionRatePercentage() : config('payroll.pension_rate', 5) * 100) }}"
                               class="form-input">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">This rate will be applied to the employee's gross pay to calculate pension.</p>
                </div>

                <div>
                    <label for="other_deductions" class="block text-sm font-medium text-gray-700">
                        Other Deductions Amount (₦) (Optional)
                    </label>
                    <input type="number" 
                           id="other_deductions" 
                           name="other_deductions" 
                           step="0.01" 
                           min="0" 
                           value="{{ old('other_deductions', isset($deduction) ? $deduction->other_deductions : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="other_deductions_description" class="block text-sm font-medium text-gray-700">
                        Other Deductions Description
                    </label>
                    <textarea id="other_deductions_description" 
                              name="other_deductions_description" 
                              rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('other_deductions_description', isset($deduction) ? $deduction->other_deductions_description : '') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.deductions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        {{ isset($deduction) ? 'Update Deduction' : 'Create Deduction' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const salaryInfo = document.getElementById('salary_info');
    const basicSalaryDisplay = document.getElementById('basic_salary_display');
    const grossPayDisplay = document.getElementById('gross_pay_display');

    function formatCurrency(amount) {
        return '₦' + new Intl.NumberFormat('en-NG', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    function updateSalaryInfo() {
        if (!employeeSelect) return;
        
        const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const basicSalary = parseFloat(selectedOption.dataset.salary) || 0;
            
            // Update displays
            basicSalaryDisplay.textContent = formatCurrency(basicSalary);
            grossPayDisplay.textContent = formatCurrency(basicSalary);
            
            // Show the salary info section
            salaryInfo.classList.remove('hidden');
        } else {
            salaryInfo.classList.add('hidden');
        }
    }

    // Add event listeners
    if (employeeSelect) {
        employeeSelect.addEventListener('change', updateSalaryInfo);
        // Initialize if a value is already selected
        if (employeeSelect.value) {
            updateSalaryInfo();
        }
    }

    // Add validation for the deduction month
    const deductionMonthInput = document.getElementById('deduction_month');
    if (deductionMonthInput) {
        deductionMonthInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const currentDate = new Date();
            
            // Prevent selecting future months
            if (selectedDate > currentDate) {
                alert('Cannot create deductions for future months');
                this.value = currentDate.toISOString().slice(0, 7);
            }
        });
    }
});
</script>
@endpush

@endsection