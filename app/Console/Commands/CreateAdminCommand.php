<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Council;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create 
                          {--email=admin@council.local : Admin email address}
                          {--password=admin123 : Admin password}
                          {--name=System Administrator : Admin full name}';

    protected $description = 'Create a default admin account for the system';

    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Get or create a default council
        $council = Council::first();
        if (!$council) {
            $council = Council::create([
                'name' => 'Default Council',
                'code' => 'DC001',
                'type' => 'municipal',
                'province' => 'Harare',
                'region' => 'Metropolitan',
                'address' => 'Default Address',
                'phone' => '000-000-0000',
                'email' => 'info@council.local',
                'active' => true,
            ]);
            $this->info('Created default council.');
        }

        // Create new admin user
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
            'council_id' => $council->id,
            'active' => true,
            'employment_status' => 'active',
            'permissions' => null, // Admin has all permissions
        ]);

        $this->info('✅ Admin account created successfully!');
        $this->table(['Field', 'Value'], [
            ['Name', $admin->name],
            ['Email', $admin->email],
            ['Password', $password],
            ['Role', $admin->role],
            ['Council', $council->name],
        ]);

        $this->warn('⚠️  Please change the default password after first login!');

        return 0;
    }
}