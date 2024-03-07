<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

        for ($i = 0; $i < 30; $i++) {
            $title = fake()->sentence(6);
            $course =  Course::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => fake()->text,
                'old_price' => rand(50, 90),
                'created_by' => 1,
                'category_id' => rand(1, 3),
                'price' => rand(0, 1) ? rand(10, 50) : '0',
                'level' => Arr::random(Course::LEVELS),
                'image' => 'courses/' . rand(1, 5) . '.webp',
                'intro' => '8kj9gY6kPfY'
            ]);
            $course->tags()->attach(Tag::take(rand(1, 5))->pluck('id'));
        }
    }
}
