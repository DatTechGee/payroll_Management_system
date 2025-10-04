<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeductionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\PayslipController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    // Welcome page
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // Admin Routes
        Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
            // Dashboard
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Department Management
            Route::resource('departments', DepartmentController::class);

            // Employee Management
            Route::resource('employees', EmployeeController::class);

            // Attendance Management
            Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance');
            Route::get('attendance/record', [AttendanceController::class, 'create'])->name('attendance.record');
            Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
            Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
            Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

            // Export Routes
            Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
            Route::get('attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');

            // Payroll Management
            Route::get('payroll/process', [PayrollController::class, 'process'])->name('payroll.process');
            Route::get('payroll/{payroll}/payslip', [PayrollController::class, 'generatePayslip'])->name('payroll.payslip');
            Route::resource('payroll', PayrollController::class);

            // Deductions Management
            Route::get('deductions', [DeductionController::class, 'index'])->name('deductions.index');
            Route::get('deductions/create', [DeductionController::class, 'create'])->name('deductions.create');
            Route::post('deductions', [DeductionController::class, 'store'])->name('deductions.store');
            Route::get('deductions/{deduction}/edit', [DeductionController::class, 'edit'])->name('deductions.edit');
            Route::put('deductions/{deduction}', [DeductionController::class, 'update'])->name('deductions.update');
            Route::delete('deductions/{deduction}', [DeductionController::class, 'destroy'])->name('deductions.destroy');

            // Profile Management
            Route::get('/profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
            Route::post('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password.update');
        });

        // Employee Routes
        Route::prefix('employee')->name('employee.')->group(function () {
            Route::get('dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

            // Payroll routes for employee
            Route::get('payroll', [App\Http\Controllers\Employee\PayrollController::class, 'index'])->name('payroll.index');
            Route::get('payroll/{payroll}', [App\Http\Controllers\Employee\PayrollController::class, 'show'])->name('payroll.show');

            // Payroll and Payslip routes
            Route::get('payroll/{payroll}/download', [App\Http\Controllers\Employee\PayrollController::class, 'downloadPayslip'])->name('payroll.download');

            // Profile Management for Employees
            Route::get('/profile/edit', [App\Http\Controllers\Employee\ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('/profile/update', [App\Http\Controllers\Employee\ProfileController::class, 'update'])->name('profile.update');
        });
    });
});
