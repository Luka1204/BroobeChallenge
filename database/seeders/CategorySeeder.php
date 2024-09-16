<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Category::factory()->create([
            'name'=>'ACCESSIBILITY'
        ]);

        Category::factory()->create([
            'name'=>'BEST_PRACTICES'
        ]);

        Category::factory()->create([
            'name'=>'PERFORMANCE'
        ]);

        Category::factory()->create([
            'name'=>'PWA'
        ]);

        Category::factory()->create([
            'name'=>'SEO'
        ]);
    }
}