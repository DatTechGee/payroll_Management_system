<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->payroll_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .info-item {
            margin-bottom: 20px;
        }
        .info-label {
            display: block;
            font-size: 14px;
            color: #4a5568;
            margin-bottom: 5px;
        }
        .info-value {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }
        .details-group {
            margin-bottom: 30px;
        }
        .details-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-item:last-child {
            border-bottom: none;
        }
        .details-label {
            color: #4a5568;
        }
        .details-value {
            font-weight: bold;
            color: #2d3748;
        }
        .total-row {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #e2e8f0;
            font-weight: bold;
        }
        .net-pay {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .net-pay-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .net-pay-label {
            font-size: 20px;
            font-weight: bold;
            color: #2d3748;
        }
        .net-pay-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c5282;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 12px;
        }
        .percentage-note {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PAYSLIP</h1>
            <p>Payslip #: {{ $payroll->payroll_id }}</p>
        </div>

        <div class="section">
            <div class="section-title">Employee Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Employee ID</span>
                    <span class="info-value">{{ $payroll->employee->employee_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Department</span>
                    <span class="info-value">{{ $payroll->employee->department->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Position</span>
                    <span class="info-value">{{ $payroll->employee->position }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Payroll Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Pay Month</span>
                    <span class="info-value">{{ $payroll->pay_month->format('F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date Processed</span>
                    <span class="info-value">{{ $payroll->date_processed->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Earnings</div>
            <div class="details-group">
                <div class="details-item">
                    <span class="details-label">Basic Salary</span>
                    <span class="details-value">₦{{ number_format($payroll->basic_salary, 2) }}</span>
                </div>
                @if($payroll->allowance)
                <div class="details-item">
                    <span class="details-label">Allowance</span>
                    <span class="details-value">₦{{ number_format($payroll->allowance, 2) }}</span>
                </div>
                @endif
                @if($payroll->bonus)
                <div class="details-item">
                    <span class="details-label">Bonus</span>
                    <span class="details-value">₦{{ number_format($payroll->bonus, 2) }}</span>
                </div>
                @endif
                <div class="details-item total-row">
                    <span class="details-label">Gross Pay</span>
                    <span class="details-value">₦{{ number_format($payroll->gross_pay, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Deductions</div>
            <div class="details-group">
                @if($payroll->deductions->isNotEmpty())
                    @php $deduction = $payroll->deductions->first(); @endphp
                    <div class="details-item">
                        <span class="details-label">Tax</span>
                        <span class="details-value">₦{{ number_format($deduction->tax, 2) }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Pension</span>
                        <span class="details-value">₦{{ number_format($deduction->pension, 2) }}</span>
                    </div>
                    @if($deduction->other_deductions > 0)
                    <div class="details-item">
                        <span class="details-label">Other Deductions</span>
                        <span class="details-value">₦{{ number_format($deduction->other_deductions, 2) }}</span>
                    </div>
                    @endif
                    <div class="details-item total-row">
                        <span class="details-label">Total Deductions</span>
                        <span class="details-value">₦{{ number_format($deduction->total_deduction, 2) }}</span>
                    </div>
                    <div class="percentage-note">
                        ({{ number_format(($deduction->total_deduction / $payroll->gross_pay) * 100, 1) }}% of gross pay)
                    </div>
                @endif
            </div>
        </div>

        <div class="net-pay">
            <div class="net-pay-row">
                <span class="net-pay-label">Net Pay</span>
                <span class="net-pay-value">₦{{ number_format($payroll->net_pay, 2) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>