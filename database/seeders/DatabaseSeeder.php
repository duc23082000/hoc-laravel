<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Course;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<25; $i++) {
            Course::create([
                'course_name' => fake()->name(),
                'price' => rand(1, 50),
                'created_by_id' => 1,
                'modified_by_id' => 1,
                'category_id' => rand(1, 3),
            ]);
        }
    }
}
