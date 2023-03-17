<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            "IT Department",
            "Finance",
            "Human Resources",
            "General Management",
            "Sales",
            "Marketing",
            "Operations",
            "Procurement",
            "Research and Development",
            "Customer Service",
        ];

        foreach($departments as $department) {
            Department::firstOrCreate(["name" => $department]);
        }
    }
}
