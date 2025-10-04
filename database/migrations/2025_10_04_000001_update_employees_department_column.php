<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only drop the old department column if it exists
        if (Schema::hasColumn('employees', 'department')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('department');
            });
        }

        // Add foreign key constraint if it doesn't exist
        if (!Schema::hasColumn('employees', 'department_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->after('password')
                      ->constrained('departments')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        // Add back the old department column
        Schema::table('employees', function (Blueprint $table) {
            $table->string('department')->after('password');
        });

        // Remove the new department_id column
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};