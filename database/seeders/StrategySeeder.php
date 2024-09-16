<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Strategy;

class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Strategy::factory()->create([
            'name'=>'DESKTOP'
        ]);

        Strategy::factory()->create([
            'name'=>'MOBILE'
        ]);
    }
}
