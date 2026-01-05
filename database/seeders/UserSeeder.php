<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admini
            ['Jan', 'Kowalski', 'admin1@mail.com', 1],
            ['Anna', 'Nowak', 'admin2@mail.com', 1],

            // Trenerzy
            ['Michał', 'Wiśniewski', 'trener1@mail.com', 2, 1],
            ['Katarzyna', 'Wójcik', 'trener2@mail.com', 2, 2],
            ['Tomasz', 'Kamiński', 'trener3@mail.com', 2, 3],
            ['Monika', 'Lewandowska', 'trener4@mail.com', 2, 4],

            // Sportowcy
            ['Piotr', 'Kaczmarek', 'sport1@mail.com', 3, 1, 2],
            ['Magdalena', 'Zielińska', 'sport2@mail.com', 3, 2, 1],
            ['Adam', 'Sikora', 'sport3@mail.com', 3, 3, 3],
            ['Ewa', 'Jankowska', 'sport4@mail.com', 3, 4, 2],
        ];

        foreach ($users as $u) {
            User::firstOrCreate([
                'name' => $u[0],
                'surname' => $u[1],
                'email' => $u[2],
                'password' => Hash::make('password'),
                'birthdate' => now()->subYears(rand(16, 45))->format('Y-m-d'),
'phone' => '6' . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9),
                'role_id' => $u[3],
                'sport_id' => $u[4] ?? null,
                'category_id' => $u[5] ?? null,
                'approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
