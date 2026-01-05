<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;


class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['role_id' => 1, 'name' => 'admin', 'created_at' => now(),
        'updated_at' => now()]);
        Role::firstOrCreate(['role_id' => 2, 'name' => 'trener', 'created_at' => now(),
        'updated_at' => now()]);
        Role::firstOrCreate(['role_id' => 3, 'name' => 'sportowiec', 'created_at' => now(),
        'updated_at' => now()]);
    }
}
