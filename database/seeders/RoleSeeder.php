<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $salesRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'sales']);
        $teknisiRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'teknisi']);
        $inputerSapRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'inputer_sap']);
        $inputerSpkRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'inputer_spk']);
    }
}
