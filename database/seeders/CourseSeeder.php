<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of course titles
        $titles = [
            'HTML 5', 'CSS 3', 'JavaScript Basics', 'Python Fundamentals', 'React.js Crash Course',
            'PHP Programming', 'Angular Essentials', 'Node.js Mastery', 'Java for Beginners',
            'Swift Programming', 'Ruby on Rails', 'Vue.js Essentials', 'SQL Bootcamp', 'C# Fundamentals',
            'Bootstrap Framework', 'Docker Deep Dive', 'Machine Learning 101', 'AWS Essentials',
            'Android App Development', 'iOS Development'
        ];

        // Create 20 courses with random titles
        for ($i = 0; $i < 20; $i++) {
            $course =  Course::create([
                'title' => $titles[$i],
                'description' => fake()->text,
                'created_by' => 1,
                'level' => Arr::random(Course::LEVELS),
                'image' => 'courses/' . rand(1, 5) . '.webp',
                'intro' => 'https://youtu.be/salY_Sm6mv4?si=KTDzG1z8oNRP9_Vb'
            ]);
            $course->tags()->attach(Tag::take(rand(1, 5))->pluck('id'));
        }
    }
}