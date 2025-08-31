<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Council;
use Illuminate\Support\Facades\Hash;

class CreateDefaultAdmin extends Command
{
    protected $signature = 'create:admin';
    protected $description = 'Create default admin user';

    public function handle()
    {
        try {
            // Create default council if it doesn't exist
            $council = Council::firstOrCreate([
                'code' => 'DEFAULT'
            ], [
                'name' => 'Default Council',
                'code' => 'DEFAULT',
                'type' => 'municipality',
                'province' => 'Harare',
                'region' => 'Harare Metropolitan',
                'address' => 'Default Address',
                'phone' => '+263000000000',
                'email' => 'admin@council.gov.zw',
                'website' => 'https://council.gov.zw',
                'active' => true
            ]);

            // Create admin user
            $user = User::firstOrCreate([
                'email' => 'admin@council.gov.zw'
            ], [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'council_id' => $council->id,
                'active' => true,
                'email_verified_at' => now()
            ]);

            if ($user->wasRecentlyCreated) {
                $this->info('Admin user created successfully!');
                $this->info('Email: admin@council.gov.zw');
                $this->info('Password: admin123');
            } else {
                $this->info('Admin user already exists!');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error creating admin user: ' . $e->getMessage());
            return 1;
        }
    }
}