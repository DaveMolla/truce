<?php

namespace Database\Seeders;

use App\Models\WinningPattern;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WinningPatternsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patterns = [
            [
                'name' => 'All Common Patterns',
                'description' => 'Includes all 14 common winning patterns.',
                'pattern_data' => json_encode([
                    [
                        [1, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0]],
                    [
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0]],
                    [
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0]
                    ],
                    [
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 1, 1],
                        [0, 0, 0, 0, 0]
                    ],
                    [
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [1, 1, 1, 1, 1]
                    ],
                    [
                        [1, 0, 0, 0, 0],
                        [1, 0, 0, 0, 0],
                        [1, 0, 0, 0, 0],
                        [1, 0, 0, 0, 0],
                        [1, 0, 0, 0, 0]
                    ],
                    [
                        [0, 1, 0, 0, 0],
                        [0, 1, 0, 0, 0],
                        [0, 1, 0, 0, 0],
                        [0, 1, 0, 0, 0],
                        [0, 1, 0, 0, 0]
                    ],
                    [
                        [0, 0, 1, 0, 0],
                        [0, 0, 1, 0, 0],
                        [0, 0, 1, 0, 0],
                        [0, 0, 1, 0, 0],
                        [0, 0, 1, 0, 0]
                    ],
                    [
                        [0, 0, 0, 1, 0],
                        [0, 0, 0, 1, 0],
                        [0, 0, 0, 1, 0],
                        [0, 0, 0, 1, 0],
                        [0, 0, 0, 1, 0]
                    ],
                    [
                        [0, 0, 0, 0, 1],
                        [0, 0, 0, 0, 1],
                        [0, 0, 0, 0, 1],
                        [0, 0, 0, 0, 1],
                        [0, 0, 0, 0, 1]
                    ],
                    [
                        [0, 0, 0, 0, 1],
                        [0, 0, 0, 1, 0],
                        [0, 0, 1, 0, 0],
                        [0, 1, 0, 0, 0],
                        [1, 0, 0, 0, 0]
                    ],
                    [
                        [1, 0, 0, 0, 0],
                        [0, 1, 0, 0, 0],
                        [0, 0, 1, 0, 0],
                        [0, 0, 0, 1, 0],
                        [0, 0, 0, 0, 1]
                    ],
                    [
                        [1, 0, 0, 0, 1],
                        [0, 0, 0, 0, 0],
                        [0, 0, 1, 0, 0],
                        [0, 0, 0, 0, 0],
                        [1, 0, 0, 0, 1]
                    ],
                    [
                        [0, 0, 0, 0, 0],
                        [0, 1, 0, 1, 0],
                        [0, 0, 1, 0, 0],
                        [0, 1, 0, 1, 0],
                        [0, 0, 0, 0, 0]
                    ],
                ])
            ],
            [
                'name' => 'Full House',
                'description' => 'Full House',
                'pattern_data' => json_encode([
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1]
                    ])
            ],
        ];

        foreach ($patterns as $pattern) {
            // Log::info('Inserting pattern:', $pattern);
            DB::table('winning_patterns')->insert([
                'name' => $pattern['name'],
                'description' => $pattern['description'],
                'pattern_data' => $pattern['pattern_data']
            ]);
        }
    }
}
