<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@pdi.com',
            'password' => Hash::make("password"),
            'role_id' => 1,
            'department_id' => 1
        ]);

        // save user permissions
        $permissions = Permission::pluck('name')->toArray();
        $user->setPermissions($permissions);
    }
}
