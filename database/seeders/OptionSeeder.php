<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = \App\Models\Question::get();
        foreach ($questions as $key => $question) {
            for ($i = 0; $i < 4; $i++) {
                $question->options()->create([
                    'order' => $i + 1,
                    'content' => fake()->sentence(),
                    'is_correct' => $i === 0,
                ]);
            }
        }
    }
}
