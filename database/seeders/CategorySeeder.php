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
        Category::firstOrCreate(
            [
                'name' => 'nowicjusz',
                'min_points' => -20,
            ]
            );
        Category::firstOrCreate(
                [
                    'name' => 'amator',
                    'min_points' => 100,
                ]
                );
        Category::firstOrCreate(
                    [
                        'name' => 'talent',
                        'min_points' => 250,
                    ]
                    );
        Category::firstOrCreate(
                        [
                            'name' => 'zawodowiec',
                            'min_points' => 500,
                        ]
                        );
    }
}
