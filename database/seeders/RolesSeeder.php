<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create([
            'name'          => 'super_admin',
            'display_name'  => 'super admin',
        ]);

        $permissions = Permission::get()->pluck('id');

        $role->givePermissions($permissions);
    }
}
