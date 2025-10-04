<?php

use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Export Routes
    Route::get('employees/export', [ExportController::class, 'exportEmployees'])->name('employees.export');
    Route::get('attendance/export', [ExportController::class, 'exportAttendance'])->name('attendance.export');
});