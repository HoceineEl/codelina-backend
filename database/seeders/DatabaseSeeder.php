<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Hoceine EL IDRISSI',
            'email' => 'contact@hoceine.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'avatar' => 'https://avatars.githubusercontent.com/u/108903912?v=4'
        ]);
        $this->call([
            TagSeeder::class,
            CourseSeeder::class,
            SectionSeeder::class,
            LessonSeeder::class,
            QuestionSeeder::class,
            OptionSeeder::class,
        ]);
    }
}