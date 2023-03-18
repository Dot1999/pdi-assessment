<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for($i = 0; $i < 5; $i++) {
            $announcement = Announcement::create([
                "title" => $faker->sentence,
                "content" => implode('. ', $faker->paragraphs()),
                "author" => 1
            ]);
            $announcement->setDepartments(Department::take(rand(1, 10))->inRandomOrder()->pluck('id')->toArray());
        }
    }
}
