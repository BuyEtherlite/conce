<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Council;
use App\Models\Department;
use App\Models\Office;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default council
        $council = Council::firstOrCreate(
            ['code' => 'MC001'],
            [
                'name' => 'Municipal Council',
                'code' => 'MC001',
                'type' => 'municipal',
                'province' => 'Harare',
                'region' => 'Metropolitan',
                'address' => '123 Municipal Street',
                'phone' => '+1-555-0123',
                'email' => 'info@council.gov',
                'website' => 'https://council.gov',
                'established_date' => '2000-01-01',
                'active' => true,
            ]
        );

        // Create Departments
        $departmentsData = [
            ['name' => 'Administration', 'code' => 'ADMIN', 'description' => 'Administrative Services'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Financial Management'],
            ['name' => 'Housing', 'code' => 'HOUSE', 'description' => 'Housing Services'],
            ['name' => 'Water', 'code' => 'WATER', 'description' => 'Water Management'],
            ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Engineering Services'],
            ['name' => 'Health', 'code' => 'HEALTH', 'description' => 'Health Services'],
        ];

        $createdDepartments = [];
        foreach ($departmentsData as $dept) {
            $department = Department::firstOrCreate([
                'name' => $dept['name'],
                'code' => $dept['code'],
            ], [
                'description' => $dept['description'],
                'council_id' => $council->id,
                'active' => true,
            ]);
            $createdDepartments[$dept['code']] = $department; // Store department for later use

            // Create a main office for each department
            Office::firstOrCreate([
                'name' => $dept['name'] . ' Office',
                'department_id' => $department->id,
            ], [
                'location' => 'Main Building',
                'council_id' => $council->id,
                'active' => true,
            ]);
        }

        // Create Super Admin User
        User::firstOrCreate([
            'email' => 'admin@council.gov',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'council_id' => $council->id,
            'department_id' => $createdDepartments['ADMIN']->id,
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Finance Manager
        User::firstOrCreate([
            'email' => 'finance@council.gov',
        ], [
            'name' => 'Finance Manager',
            'password' => Hash::make('finance123'),
            'role' => 'manager',
            'council_id' => $council->id,
            'department_id' => $createdDepartments['FIN']->id,
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Super Admin: admin@council.gov / admin123');
        $this->command->info('Finance Manager: finance@council.gov / finance123');
    }
}