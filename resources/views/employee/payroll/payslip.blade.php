@php
    // Define Naira symbol directly
    $naira = 'NGN ';
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Employee Payslip - {{ $payroll->payroll_id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .container {
                padding: 0;
            }
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-info {
            margin-bottom: 20px;
            text-align: center;
        }
        .company-info h2 {
            margin: 0 0 5px;
            color: #2c3e50;
        }
        .company-info p {
            margin: 0;
            font-size: 12px;
            color: #7f8c8d;
        }
        .confidential {
            color: #dc3545;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .payslip-title {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .payslip-period {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #34495e;
            font-size: 12px;
        }
        .info-value {
            color: #2c3e50;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
        }
        .amount {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .net-pay {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            text-align: right;
        }
        .net-pay-label {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        .net-pay-amount {
            font-size: 20px;
            color: #27ae60;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #bdc3c7;
            font-size: 10px;
            color: #7f8c8d;
            text-align: center;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <h2>PayrollMS</h2>
                <p>123 Business Street</p>
                <p>City, State 12345</p>
            </div>
            <div class="confidential">CONFIDENTIAL</div>
            <h1 class="payslip-title">EMPLOYEE PAYSLIP</h1>
            <div class="payslip-period">
                For the month of {{ $payroll->pay_month->format('F Y') }}
            </div>
            <div>Payslip #: {{ $payroll->payroll_id }}</div>
        </div>

        <div class="section">
            <div class="info-grid">
                <div>
                    <div class="section-title">Employee Information</div>
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $payroll->employee->full_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Employee ID:</span>
                        <span class="info-value">{{ $payroll->employee->employee_id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department:</span>
                        <span class="info-value">{{ $payroll->employee->department->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Position:</span>
                        <span class="info-value">{{ $payroll->employee->position }}</span>
                    </div>
                </div>
                <div>
                    <div class="section-title">Payment Information</div>
                    <div class="info-item">
                        <span class="info-label">Pay Period:</span>
                        <span class="info-value">{{ $payroll->pay_month->format('F Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment Date:</span>
                        <span class="info-value">{{ $payroll->date_processed->format('F d, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Working Days:</span>
                        <span class="info-value">{{ $payroll->working_days }} days</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>Earnings</th>
                        <th class="amount">Amount</th>
                        <th>Deductions</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>Tax</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->deductions->first()->tax ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Allowance</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->allowance, 2) }}</td>
                        <td>Pension</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->deductions->first()->pension ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Overtime ({{ $payroll->overtime_hours }} hours)</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->overtime_pay, 2) }}</td>
                        <td>Other Deductions</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->deductions->first()->other_deductions ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Bonus</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->bonus, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Earnings</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->gross_pay, 2) }}</td>
                        <td>Total Deductions</td>
                        <td class="amount">{{ $naira }}{{ number_format($payroll->deductions->first()->total_deduction ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="net-pay">
                <span class="net-pay-label">NET PAY:</span>
                <span class="net-pay-amount">{{ $naira }}{{ number_format($payroll->net_pay, 2) }}</span>
            </div>
        </div>

        @if($payroll->deductions->first()->other_deductions_description)
        <div class="section">
            <div class="section-title">Deduction Notes</div>
            <p>{{ $payroll->deductions->first()->other_deductions_description }}</p>
        </div>
        @endif

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Employee Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer-generated document and serves as your official payslip.</p>
            <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>