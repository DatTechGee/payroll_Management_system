<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('pension', 10, 2)->nullable()->default(0);
            $table->decimal('pension_rate', 5, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->nullable()->default(0);
            $table->text('other_deductions_description')->nullable();
            $table->decimal('total_deduction', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deductions');
    }
};