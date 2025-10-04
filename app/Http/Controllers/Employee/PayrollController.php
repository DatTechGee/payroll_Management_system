<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with(['deductions'])
            ->where('employee_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('employee.payroll.index', compact('payrolls'));
    }

    public function show(Payroll $payroll)
    {
        // Ensure the payroll belongs to the logged-in employee
        if ($payroll->employee_id !== auth()->id()) {
            abort(403);
        }

        $payroll->load('deductions');

        return view('employee.payroll.show', compact('payroll'));
    }

    /**
     * Download payslip as PDF
     */
    public function downloadPayslip(Payroll $payroll)
    {
        // Ensure the payroll belongs to the logged-in employee
        if ($payroll->employee_id !== auth()->id()) {
            abort(403);
        }

        $payroll->load(['employee', 'deductions']);

        // Configure DomPDF default options
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('employee.payroll.payslip', compact('payroll'));
        $dompdf = $pdf->getDomPDF();
        $dompdf->setOptions($options);

        // Set paper size and orientation
        $pdf->setPaper('A4');

        // Return the PDF for download
        return $pdf->download("payslip-{$payroll->payroll_id}.pdf");
    }
}
