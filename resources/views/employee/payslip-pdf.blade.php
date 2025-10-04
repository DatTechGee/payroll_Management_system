<!DOCTYPE html>
<html>
<head>
    <title>Payslip - {{ $payroll->pay_month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .payslip-title {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .amount-section {
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .total-row {
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Company Name</div>
        <div class="payslip-title">PAYSLIP FOR {{ strtoupper($payroll->pay_month) }}</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Employee Name:</span>
            <span>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Employee ID:</span>
            <span>{{ $payroll->employee->employee_id }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Department:</span>
            <span>{{ $payroll->employee->department->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Position:</span>
            <span>{{ $payroll->employee->position }}</span>
        </div>
    </div>

    <div class="amount-section">
        <!-- Earnings -->
        <div style="margin: 10px 0; border-bottom: 1px solid #ddd;">
            <strong>Earnings:</strong>
        </div>
        <div class="info-row">
            <span class="info-label">Basic Salary:</span>
            <span>₱{{ number_format($payroll->basic_salary, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Allowance:</span>
            <span>₱{{ number_format($payroll->allowance, 2) }}</span>
        </div>
        @if($payroll->overtime_hours > 0)
        <div class="info-row">
            <span class="info-label">Overtime ({{ $payroll->overtime_hours }} hrs):</span>
            <span>₱{{ number_format($payroll->overtime_pay, 2) }}</span>
        </div>
        @endif
        @if($payroll->bonus > 0)
        <div class="info-row">
            <span class="info-label">Bonus:</span>
            <span>₱{{ number_format($payroll->bonus, 2) }}</span>
        </div>
        @endif
        <div class="info-row" style="border-top: 1px solid #ddd; padding-top: 5px;">
            <span class="info-label">Total Earnings:</span>
            <span>₱{{ number_format($payroll->gross_pay, 2) }}</span>
        </div>
        
        <!-- Deductions -->
        <div style="margin: 20px 0 10px 0; border-bottom: 1px solid #ddd;">
            <strong>Deductions:</strong>
        </div>
        <div class="info-row">
            <span class="info-label">Tax (10%):</span>
            <span>₱{{ number_format($payroll->tax_deduction, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pension (5%):</span>
            <span>₱{{ number_format($payroll->pension_deduction, 2) }}</span>
        </div>
        @if($payroll->other_deductions > 0)
        <div class="info-row">
            <span class="info-label">Other Deductions:</span>
            <span>₱{{ number_format($payroll->other_deductions, 2) }}</span>
        </div>
        @endif
        <div class="info-row" style="border-top: 1px solid #ddd; padding-top: 5px;">
            <span class="info-label">Total Deductions:</span>
            <span>₱{{ number_format(
                $payroll->tax_deduction +
                $payroll->pension_deduction +
                $payroll->other_deductions,
                2
            ) }}</span>
        </div>

        <div class="total-row info-row">
            <span class="info-label">Net Pay:</span>
            <span>₱{{ number_format($payroll->net_pay, 2) }}</span>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Bank Name:</span>
            <span>{{ $payroll->employee->bank_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Account Number:</span>
            <span>{{ $payroll->employee->account_number }}</span>
        </div>
    </div>

    <div style="margin-top: 50px; font-size: 10px; text-align: center;">
        This is a computer-generated document. No signature is required.
    </div>
</body>
</html>