<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_id')->unique();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('pay_month');
            $table->unique(['employee_id', 'pay_month']); // Ensure one payroll per employee per month
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('allowance', 10, 2)->nullable()->default(0);
            $table->decimal('overtime_hours', 8, 2)->nullable()->default(0);
            $table->decimal('overtime_pay', 10, 2)->nullable()->default(0);
            $table->decimal('bonus', 10, 2)->nullable()->default(0);
            $table->decimal('gross_pay', 10, 2)->default(0);
            $table->decimal('tax_deduction', 10, 2)->default(0);
            $table->decimal('pension_deduction', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('net_pay', 10, 2)->default(0);
            $table->integer('working_days')->default(0);
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->date('date_processed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
};