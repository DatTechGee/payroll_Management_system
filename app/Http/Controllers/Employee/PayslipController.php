<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    public function index()
    {
        $employee = Auth::user();
        $payslips = Payroll::with('deductions')
            ->where('employee_id', $employee->id)
            ->orderBy('pay_month', 'desc')
            ->paginate(10);

        return view('employee.payslip', compact('payslips'));
    }

    public function download(Payroll $payroll)
    {
        if ($payroll->employee_id !== Auth::id()) {
            abort(403);
        }

        $payroll->load(['deductions', 'employee']);
        $pdf = Pdf::loadView('employee.payslip-pdf', compact('payroll'));
        
        return $pdf->download('payslip-' . $payroll->pay_month . '.pdf');
    }
}