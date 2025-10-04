<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Information Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance & Accounting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Digital Marketing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operations Management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales & Business Development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Research & Development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Customer Support',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quality Assurance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Product Management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Legal & Compliance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Analytics',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}