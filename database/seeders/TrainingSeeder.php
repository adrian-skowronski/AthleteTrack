<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        // Przykładowi trenerzy – ręcznie podane user_id
        $trainings = [
            ['description' => 'Technika biegu', 'date' => '2026-03-01', 'max_points' => 10, 'trainer_id' => 3],
            ['description' => 'Siła ogólna', 'date' => '2026-03-03', 'max_points' => 12, 'trainer_id' => 4],
            ['description' => 'Stabilizacja', 'date' => '2026-03-05', 'max_points' => 11, 'trainer_id' => 3],
            ['description' => 'Koordynacja', 'date' => '2026-03-07', 'max_points' => 13, 'trainer_id' => 4],
            ['description' => 'Wytrzymałość', 'date' => '2026-03-09', 'max_points' => 15, 'trainer_id' => 5],
            ['description' => 'Szybkość', 'date' => '2026-03-11', 'max_points' => 14, 'trainer_id' => 5],
            ['description' => 'Rozciąganie', 'date' => '2026-03-13', 'max_points' => 8, 'trainer_id' => 3],
            ['description' => 'Start niski', 'date' => '2026-03-15', 'max_points' => 12, 'trainer_id' => 4],
            ['description' => 'Skoczność', 'date' => '2026-03-17', 'max_points' => 13, 'trainer_id' => 5],
            ['description' => 'Mobilność', 'date' => '2026-03-19', 'max_points' => 10, 'trainer_id' => 3],
            ['description' => 'Interwały', 'date' => '2026-03-21', 'max_points' => 15, 'trainer_id' => 4],
            ['description' => 'Rzut techniczny', 'date' => '2026-03-23', 'max_points' => 14, 'trainer_id' => 5],
            ['description' => 'Analiza wideo', 'date' => '2026-03-25', 'max_points' => 9, 'trainer_id' => 3],
            ['description' => 'Trening regeneracyjny', 'date' => '2026-03-27', 'max_points' => 8, 'trainer_id' => 4],
            ['description' => 'Test sprawnościowy', 'date' => '2026-03-29', 'max_points' => 16, 'trainer_id' => 5],
        ];

        foreach ($trainings as $t) {
            Training::firstOrCreate([
                'description' => $t['description'],
                'date' => $t['date'],
                'start_time' => '17:00:00',
                'end_time' => '18:30:00',
                'trainer_id' => $t['trainer_id'],
                'max_points' => $t['max_points'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
