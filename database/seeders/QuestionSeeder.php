<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = Lesson::where('type', 'quiz')->get();
        foreach ($lessons as $key => $lesson) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                Question::create([
                    'lesson_id' => $lesson->id,
                    'order' => $i + 1,
                    'content' => fake()->sentence() . '?',
                ]);
            }
        }
    }
}