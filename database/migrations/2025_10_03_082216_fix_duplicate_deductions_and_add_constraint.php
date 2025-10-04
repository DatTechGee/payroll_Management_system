<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, identify all payroll_ids with multiple deductions
        $duplicatePayrollIds = DB::table('deductions')
            ->select('payroll_id')
            ->groupBy('payroll_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('payroll_id');

        foreach ($duplicatePayrollIds as $payrollId) {
            // Keep only the latest deduction for each payroll_id
            $latestDeductionId = DB::table('deductions')
                ->where('payroll_id', $payrollId)
                ->latest('created_at')
                ->value('id');

            // Delete all other deductions for this payroll_id
            DB::table('deductions')
                ->where('payroll_id', $payrollId)
                ->where('id', '!=', $latestDeductionId)
                ->delete();
        }

        // Now that we've cleaned up duplicates, add the unique constraint
        Schema::table('deductions', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            $table->dropForeign(['payroll_id']);
            
            // Add unique constraint
            $table->unique('payroll_id');
            
            // Recreate foreign key
            $table->foreign('payroll_id')
                  ->references('id')
                  ->on('payrolls')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deductions', function (Blueprint $table) {
            $table->dropForeign(['payroll_id']);
            $table->dropUnique(['payroll_id']);
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
        });
    }
};
