<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::get();
        foreach ($courses as $key => $course) {
            for ($i = 0; $i < rand(1, 8); $i++) {
                Section::create([
                    'title' => fake()->sentence(),
                    'order' => $course->sections->count() + 1,
                    'course_id' => $course->id,
                    'description' => rand(0, 1) ? '' : fake()->text(),
                ]);
            }
        }
    }
}