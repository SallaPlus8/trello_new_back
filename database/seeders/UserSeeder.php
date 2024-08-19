<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate([

            'name'          =>  'admin',
            'email'         => 'admin@alyoum.com',
            'password'      => Hash::make('01066943748'),
           'super_admin'   => 1
        ]);

        // $admin->addRole('super_admin');

        // User::firstOrCreate([

        //     'name'          =>  'user',
        //     'email'         => 'user@example.com',
        //     'password'      => Hash::make('123456')
        // ]);
        // User::firstOrCreate([

        //     'name'          =>  'super admin',
        //     'email'         => 'superadmin@example.com',
        //     'password'      => Hash::make('123456'),
        //     'super_admin'   => 1
        // ]);
    }
}
