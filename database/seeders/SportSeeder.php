<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = [
            'biegi krótkodystansowe',
            'biegi długodystansowe',
            'skok w dal',
            'skok wzwyż',
            'rzut oszczepem',
            'trójskok',
        ];

        foreach ($sports as $sport) {
            Sport::firstOrCreate([
                'name' => $sport,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
