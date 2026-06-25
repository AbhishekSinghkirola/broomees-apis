<?php

namespace Database\Seeders;

use App\Models\Hobby;
use Illuminate\Database\Seeder;

class HobbySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hobbies = [
            'Reading',
            'Coding',
            'Gaming',
            'Cooking',
            'Traveling',
            'Photography',
            'Painting'
        ];

        foreach ($hobbies as $hobby) {
            Hobby::firstOrCreate([
                'name' => $hobby,
            ]);
        }
    }
}
