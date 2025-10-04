<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // First, ensure there's an Administration department
        $department = \App\Models\Department::firstOrCreate([
            'name' => 'Administration'
        ]);

        Employee::create([
            'employee_id' => 'ADMIN001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'department_id' => $department->id,
            'position' => 'System Administrator',
            'date_hired' => now(),
            'basic_salary' => 0,
            'bank_name' => 'N/A',
            'account_number' => 'N/A',
            'role' => 'admin',
        ]);
    }
}