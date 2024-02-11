<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = Section::get();
        foreach ($sections as $key => $section) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                $type = Arr::random(Lesson::TYPES);
                Lesson::create([
                    'title' => fake()->sentence(),
                    'type' => $type,
                    'order' => $section->lessons->count() + 1,
                    'url' => $type === Lesson::VIDEO ? 'https://www.youtube.com/watch?v=IKmlDnItT_A' : null,
                    'article' => $type === Lesson::ARTICLE ?  fake()->text(800) : null,
                    'section_id' => $section->id,
                ]);
            }
        }
    }
}