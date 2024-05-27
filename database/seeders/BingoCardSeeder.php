<?php

namespace Database\Seeders;

use App\Models\BingoCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BingoCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $bingoCards = [
            [
                'card_data' => json_encode([
                    [1, 16, 31, 46, 61],
                    [2, 17, 33, 47, 62],
                    [3, 18, 'FREE', 48, 63],
                    [4, 19, 34, 49, 64],
                    [5, 20, 35, 50, 65],
                ]),
            ],
            [
                'card_data' => json_encode([
                    [6, 21, 36, 51, 66],
                    [7, 22, 38, 52, 67],
                    [8, 23, 'FREE', 53, 68],
                    [9, 24, 39, 54, 69],
                    [10, 25, 40, 55, 70],
                ]),
            ],
            [
                'card_data' => json_encode([
                    [11, 26, 41, 56, 71],
                    [12, 27, 43, 57, 72],
                    [13, 28, 'FREE', 58, 73],
                    [14, 29, 44, 59, 74],
                    [15, 30, 45, 60, 75],
                ]),
            ],
        ];

        foreach ($bingoCards as $card) {
            BingoCard::create($card);
        }
    }
}
