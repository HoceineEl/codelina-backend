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
                    'order' => $i + 1,
                    'video_id' => $type === Lesson::VIDEO ? 'IKmlDnItT_A' : null,
                    'duration' => ($type === Lesson::VIDEO) ? 12 : (($type === Lesson::ARTICLE) ? 5 : 0),
                    'article' => $type === Lesson::ARTICLE ?  fake()->paragraph(30) : null,
                    'section_id' => $section->id,
                ]);
            }
        }
    }
}