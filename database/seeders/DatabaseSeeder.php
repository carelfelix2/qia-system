<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'status' => 'approved',
        ]);
        $admin->assignRole('admin');

        $sales = User::factory()->create([
            'name' => 'Sales User',
            'email' => 'sales@example.com',
            'username' => 'sales',
            'password' => bcrypt('password'),
            'status' => 'approved',
        ]);
        $sales->assignRole('sales');

        $teknisi = User::factory()->create([
            'name' => 'Teknisi User',
            'email' => 'teknisi@example.com',
            'username' => 'teknisi',
            'password' => bcrypt('password'),
            'status' => 'approved',
        ]);
        $teknisi->assignRole('teknisi');
    }
}
