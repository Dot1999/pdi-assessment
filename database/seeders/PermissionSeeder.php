<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules_permissions = [
            "department" => ["create","update","delete","view","export"],
            "announcement" => ["create","update","delete","view","export"],
            "user" => ["create","update","delete","view","export"],
            "logs" => ["view","export"],
        ];

        foreach($modules_permissions as $module => $permissions) {
            foreach($permissions as $permission) {
                Permission::firstOrCreate(["name" => "$module-$permission"]);
            }
        }
    }
}
