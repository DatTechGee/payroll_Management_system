<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('department');
            $table->string('position');
            $table->date('date_hired');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowance', 10, 2)->nullable();
            $table->string('bank_name');
            $table->string('account_number');
            $table->enum('role', ['admin', 'employee'])->default('employee');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
