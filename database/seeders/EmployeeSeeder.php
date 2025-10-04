<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Get all departments
        $departments = Department::all();

        if ($departments->isEmpty()) {
            // Run DepartmentSeeder first if no departments exist
            $this->call(DepartmentSeeder::class);
            $departments = Department::all();
        }

        $employees = [
            [
                'employee_id' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('12345'),
                'department_id' => $departments->where('name', 'Sales & Business Development')->first()->id,
                'position' => 'Sales Manager',
                'date_hired' => '2025-01-15',
                'basic_salary' => 150000.00,
                'allowance' => 15000.00,
                'bank_name' => 'First Bank',
                'account_number' => '1234567890',
                'role' => 'employee',
            ],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('12345'),
                'department_id' => $departments->where('name', 'Digital Marketing')->first()->id,
                'position' => 'Marketing Coordinator',
                'date_hired' => '2025-02-01',
                'basic_salary' => 120000.00,
                'allowance' => 12000.00,
                'bank_name' => 'UBA',
                'account_number' => '0987654321',
                'role' => 'employee',
            ],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael@example.com',
                'password' => Hash::make('12345'),
                'department_id' => $departments->where('name', 'Information Technology')->first()->id,
                'position' => 'Software Developer',
                'date_hired' => '2025-03-01',
                'basic_salary' => 200000.00,
                'allowance' => 20000.00,
                'bank_name' => 'Access Bank',
                'account_number' => '5678901234',
                'role' => 'employee',
            ],
            [
                'employee_id' => 'EMP004',
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah@example.com',
                'password' => Hash::make('12345'),
                'department_id' => $departments->where('name', 'Human Resources')->first()->id,
                'position' => 'HR Manager',
                'date_hired' => '2025-01-10',
                'basic_salary' => 180000.00,
                'allowance' => 18000.00,
                'bank_name' => 'Zenith Bank',
                'account_number' => '3456789012',
                'role' => 'employee',
            ],
            [
                'employee_id' => 'EMP005',
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david@example.com',
                'password' => Hash::make('12345'),
                'department_id' => $departments->where('name', 'Finance & Accounting')->first()->id,
                'position' => 'Accountant',
                'date_hired' => '2025-02-15',
                'basic_salary' => 160000.00,
                'allowance' => 16000.00,
                'bank_name' => 'GTBank',
                'account_number' => '8901234567',
                'role' => 'employee',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
