<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WinningPatternsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patterns = [
            // [
            //     'name' => 'Both Diagonal Line',
            //     'description' => 'Pattern description for both diagonal line.',
            //     'pattern_data' => json_encode([
            //         [1, 0, 0, 0, 1],
            //         [0, 1, 0, 1, 0],
            //         [0, 0, 1, 0, 0],
            //         [0, 1, 0, 1, 0],
            //         [1, 0, 0, 0, 1]
            //     ])
            // ],
            // [
            //     'name' => 'Horizontal Line',
            //     'description' => 'Pattern description for horizontal line.',
            //     'pattern_data' => json_encode([
            //         [1, 1, 1, 1, 1],
            //         [0, 0, 0, 0, 0],
            //         [0, 0, 0, 0, 0],
            //         [0, 0, 0, 0, 0],
            //         [0, 0, 0, 0, 0]
            //     ])
            // ],
            [
                'name' => 'Full House',
                'description' => 'Pattern description for Full House',
                'pattern_data' => json_encode([
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1]
                ])
            ],
            // Add more patterns as needed
        ];
        DB::table('winning_patterns')->insert($patterns);

    }
}
