<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // First create the new column
            $table->foreignId('department_id')->nullable()->after('password');
            
            // Remove the old column
            $table->dropColumn('department');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Recreate the old column
            $table->string('department')->after('password');
            
            // Remove the new column
            $table->dropColumn('department_id');
        });
    }
};