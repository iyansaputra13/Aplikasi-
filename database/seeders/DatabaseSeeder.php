<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Inventory',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Cashier Users
        User::create([
            'name' => 'Kasir 1',
            'email' => 'cashier1@inventory.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
        ]);

        User::create([
            'name' => 'Kasir 2',
            'email' => 'cashier2@inventory.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
        ]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('');
        $this->command->info('📧 Admin Login:');
        $this->command->info('   Email: admin@inventory.com');
        $this->command->info('   Password: admin123');
        $this->command->info('');
        $this->command->info('📧 Cashier Login:');
        $this->command->info('   Email: cashier1@inventory.com');
        $this->command->info('   Password: cashier123');
    }
}