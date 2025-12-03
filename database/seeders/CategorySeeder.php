<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'description' => 'Math assignments and exercises', 'is_active' => true],
            ['name' => 'Science', 'slug' => 'science', 'description' => 'Science assignments and experiments', 'is_active' => true],
            ['name' => 'Literature', 'slug' => 'literature', 'description' => 'Literature and writing assignments', 'is_active' => true],
            ['name' => 'Programming', 'slug' => 'programming', 'description' => 'Programming and coding assignments', 'is_active' => true],
            ['name' => 'General', 'slug' => 'general', 'description' => 'General assignments', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
