@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Edit Deduction</h2>
                <a href="{{ route('admin.deductions.index') }}" 
                   class="text-indigo-600 hover:text-indigo-900">
                    Back to List
                </a>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

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

            <!-- Summary of current deductions -->
            <div class="mb-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <h3 class="text-lg font-medium text-blue-900 mb-4">Deduction Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/50 backdrop-blur-sm rounded-lg p-4 shadow-sm">
                        <p class="text-sm font-medium text-blue-600">Gross Pay</p>
                        <p class="text-xl font-bold text-blue-900">₦{{ number_format($deduction->payroll->gross_pay, 2) }}</p>
                    </div>
                    <div class="bg-white/50 backdrop-blur-sm rounded-lg p-4 shadow-sm">
                        <p class="text-sm font-medium text-blue-600">Total Deductions</p>
                        <p class="text-xl font-bold text-blue-900" id="totalDeductions">₦{{ number_format($deduction->total_deduction, 2) }}</p>
                    </div>
                    <div class="bg-white/50 backdrop-blur-sm rounded-lg p-4 shadow-sm">
                        <p class="text-sm font-medium text-blue-600">Net Pay</p>
                        <p class="text-xl font-bold text-blue-900" id="calculatedNetPay">₦{{ number_format($deduction->payroll->net_pay, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.deductions.update', $deduction) }}" 
                  method="POST" 
                  class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Hidden fields for calculated values -->
                <input type="hidden" id="tax" name="tax" value="{{ $deduction->tax }}">
                <input type="hidden" id="pension" name="pension" value="{{ $deduction->pension }}">

                <div>
                    <label for="tax_rate" class="form-label">
                        Tax Rate (%)
                    </label>
                    <div class="mt-1 grid grid-cols-2 gap-4">
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" 
                                   id="tax_rate" 
                                   name="tax_rate" 
                                   step="0.01" 
                                   min="0"
                                   max="100"
                                   required 
                                   value="{{ old('tax_rate', $taxRate) }}"
                                   class="mt-1 block w-full rounded-md shadow-sm pr-12 {{ $errors->has('tax_rate') ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}"
                                   oninput="calculateTax(this.value)">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg px-4">
                            <span class="text-sm text-blue-700 font-medium">Amount: </span>
                            <span id="calculatedTax" class="ml-2 text-lg font-bold text-blue-800">₦{{ number_format($deduction->tax, 2) }}</span>
                        </div>
                    </div>
                    @error('tax_rate')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        This rate will be applied to the employee's gross pay (₦{{ number_format($deduction->payroll->gross_pay, 2) }}) to calculate tax.
                        <br>
                        <span class="font-medium">Calculated Tax: </span>
                        <span id="calculatedTax">₦{{ number_format($deduction->tax, 2) }}</span>
                    </p>
                </div>

                <div>
                    <label for="pension_rate" class="form-label">
                        Pension Rate (%)
                    </label>
                    <div class="mt-1 grid grid-cols-2 gap-4">
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" 
                                   id="pension_rate" 
                                   name="pension_rate" 
                                   step="0.01" 
                                   min="0"
                                   max="100"
                                   required 
                                   value="{{ old('pension_rate', $pensionRate) }}"
                                   class="mt-1 block w-full rounded-md shadow-sm pr-12 {{ $errors->has('pension_rate') ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}"
                                   oninput="calculatePension(this.value)">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg px-4">
                            <span class="text-sm text-blue-700 font-medium">Amount: </span>
                            <span id="calculatedPension" class="ml-2 text-lg font-bold text-blue-800">₦{{ number_format($deduction->pension, 2) }}</span>
                        </div>
                    </div>
                    @error('pension_rate')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        This rate will be applied to the employee's gross pay to calculate pension.
                        <br>
                        <span class="font-medium">Calculated Pension: </span>
                        <span id="calculatedPension">₦{{ number_format($deduction->pension, 2) }}</span>
                    </p>
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
                           value="{{ old('other_deductions', $deduction->other_deductions) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           onchange="updateOtherDeductions(this.value)">
                </div>

                <div>
                    <label for="other_deductions_description" class="block text-sm font-medium text-gray-700">
                        Other Deductions Description
                    </label>
                    <textarea id="other_deductions_description" 
                              name="other_deductions_description" 
                              rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('other_deductions_description', $deduction->other_deductions_description) }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.deductions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        Update Deduction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const grossPay = {{ $deduction->payroll->gross_pay }};
    
    function formatCurrency(amount) {
        return '₦' + new Intl.NumberFormat('en-NG', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    function calculateAmount(rate) {
        return (grossPay * (parseFloat(rate || 0) / 100));
    }

    function calculateTax(rate) {
        const taxAmount = calculateAmount(rate);
        document.getElementById('calculatedTax').textContent = formatCurrency(taxAmount);
        document.getElementById('tax').value = taxAmount.toFixed(2);
        updateDeductionSummary();
    }

    function calculatePension(rate) {
        const pensionAmount = calculateAmount(rate);
        document.getElementById('calculatedPension').textContent = formatCurrency(pensionAmount);
        document.getElementById('pension').value = pensionAmount.toFixed(2);
        updateDeductionSummary();
    }

    function updateOtherDeductions(amount) {
        const otherAmount = parseFloat(amount || 0);
        if (document.getElementById('calculatedOther')) {
            document.getElementById('calculatedOther').textContent = formatCurrency(otherAmount);
        }
        updateDeductionSummary();
    }

    function updateDeductionSummary() {
        const tax = calculateAmount(document.getElementById('tax_rate').value);
        const pension = calculateAmount(document.getElementById('pension_rate').value);
        const other = parseFloat(document.getElementById('other_deductions').value || 0);
        
        const totalDeductions = tax + pension + other;
        const netPay = grossPay - totalDeductions;
        
        // Update the summary section with smooth transitions
        const totalElement = document.getElementById('totalDeductions');
        const netPayElement = document.getElementById('calculatedNetPay');
        
        if (totalElement && netPayElement) {
            totalElement.textContent = formatCurrency(totalDeductions);
            netPayElement.textContent = formatCurrency(netPay);
            
            // Add a subtle highlight effect
            [totalElement, netPayElement].forEach(el => {
                el.classList.add('bg-blue-50');
                setTimeout(() => el.classList.remove('bg-blue-50'), 300);
            });
        }
    }

    function validateDeductions(e) {
        const taxRate = parseFloat(document.getElementById('tax_rate').value);
        const pensionRate = parseFloat(document.getElementById('pension_rate').value);
        const otherDeductions = parseFloat(document.getElementById('other_deductions').value || 0);
        
        let errors = [];
        
        if (isNaN(taxRate) || taxRate < 0 || taxRate > 100) {
            errors.push('Tax rate must be between 0 and 100');
        }
        
        if (isNaN(pensionRate) || pensionRate < 0 || pensionRate > 100) {
            errors.push('Pension rate must be between 0 and 100');
        }
        
        if (isNaN(otherDeductions) || otherDeductions < 0) {
            errors.push('Other deductions cannot be negative');
        }
        
        const totalDeductions = calculateAmount(taxRate) + calculateAmount(pensionRate) + otherDeductions;
        if (totalDeductions >= grossPay) {
            errors.push('Total deductions cannot exceed gross pay');
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    }

    // Initialize calculations and set up event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = ['tax_rate', 'pension_rate', 'other_deductions'].map(id => 
            document.getElementById(id)
        );
        
        // Initial calculations
        calculateTax(document.getElementById('tax_rate').value);
        calculatePension(document.getElementById('pension_rate').value);
        updateOtherDeductions(document.getElementById('other_deductions').value);
        
        // Set up real-time update listeners with debouncing
        let updateTimeout;
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', function() {
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(() => {
                        switch(this.id) {
                            case 'tax_rate':
                                calculateTax(this.value);
                                break;
                            case 'pension_rate':
                                calculatePension(this.value);
                                break;
                            case 'other_deductions':
                                updateOtherDeductions(this.value);
                                break;
                        }
                    }, 100);
                });
            }
        });
        
        // Form validation
        if (form) {
            form.addEventListener('submit', validateDeductions);
        }
    });
</script>
@endpush
@endsection