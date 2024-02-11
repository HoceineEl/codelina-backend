<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of tags
        $tags = ['HTML', 'CSS', 'JavaScript', 'VueJS', 'PHP', 'React', 'Angular', 'Node.js', 'NuxtJs', 'Swift'];

        // Create 10 tags with random names
        for ($i = 0; $i < 10; $i++) {
            Tag::create([
                'name' => $tags[$i],
            ]);
        }
    }
}
