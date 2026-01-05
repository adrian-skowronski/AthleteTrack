<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['name' => 'Otwarte Zawody Regionalne', 'date' => '2026-03-02', 'category' => 1, 'age_from' => 12, 'age_to' => 18],
            ['name' => 'Halowy Turniej Wiosenny', 'date' => '2026-03-03', 'category' => 2, 'age_from' => 18, 'age_to' => 30],
            ['name' => 'Puchar Młodych Talentów', 'date' => '2026-03-04', 'category' => 1, 'age_from' => 10, 'age_to' => 16],
            ['name' => 'Liga Treningowa', 'date' => '2026-03-05', 'category' => 1, 'age_from' => 15, 'age_to' => 25],
            ['name' => 'Memoriał Sportowy', 'date' => '2026-03-06', 'category' => 3, 'age_from' => 20, 'age_to' => 35],
            ['name' => 'Zawody Międzyszkolne', 'date' => '2026-03-08', 'category' => 1, 'age_from' => 8, 'age_to' => 14],
            ['name' => 'Otwarte Mistrzostwa Miasta', 'date' => '2026-03-10', 'category' => 2, 'age_from' => 18, 'age_to' => 40],
            ['name' => 'Eliminacje Wojewódzkie', 'date' => '2026-03-11', 'category' => 3, 'age_from' => 25, 'age_to' => 45],
            ['name' => 'Grand Prix Wiosny', 'date' => '2026-03-13', 'category' => 2, 'age_from' => 16, 'age_to' => 30],
            ['name' => 'Zawody Kontrolne', 'date' => '2026-03-14', 'category' => 1, 'age_from' => 12, 'age_to' => 18],
            ['name' => 'Puchar Trenerów', 'date' => '2026-03-16', 'category' => 3, 'age_from' => 22, 'age_to' => 40],
            ['name' => 'Mityng Lekkoatletyczny', 'date' => '2026-03-18', 'category' => 2, 'age_from' => 15, 'age_to' => 35],
            ['name' => 'Zawody Klubowe', 'date' => '2026-03-20', 'category' => 1, 'age_from' => 10, 'age_to' => 20],
            ['name' => 'Turniej Otwarty', 'date' => '2026-03-22', 'category' => 2, 'age_from' => 18, 'age_to' => 50],
            ['name' => 'Mistrzostwa Regionu', 'date' => '2026-03-25', 'category' => 4, 'age_from' => 25, 'age_to' => 60],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate([
                'required_category_id' => $event['category'],
                'age_from' => $event['age_from'],
                'age_to' => $event['age_to'],
                'name' => $event['name'],
                'description' => 'Zawody sportowe',
                'date' => $event['date'],
                'start_hour' => '10:00:00',
                'max_participants' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
