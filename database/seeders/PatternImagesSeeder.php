<?php

namespace Database\Seeders;

use App\Models\WinningPattern;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatternImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // Insert images for 'All Common Patterns'
    $winningPatternAllCommon = WinningPattern::where('name', 'All Common Patterns')->first();
    if ($winningPatternAllCommon) {
        $imagePathsAllCommon = [
            'images/all-common-patterns/1.png',
            'images/all-common-patterns/2.png',
            'images/all-common-patterns/3.png',
            'images/all-common-patterns/4.png',
            'images/all-common-patterns/5.png',
            'images/all-common-patterns/6.png',
            'images/all-common-patterns/7.png',
            'images/all-common-patterns/8.png',
            'images/all-common-patterns/9.png',
            'images/all-common-patterns/10.png',
            'images/all-common-patterns/11.png',
            'images/all-common-patterns/12.png',
            'images/all-common-patterns/13.png',
            'images/all-common-patterns/14.png',
        ];

        foreach ($imagePathsAllCommon as $path) {
            DB::table('pattern_images')->insert([
                'winning_pattern_id' => $winningPatternAllCommon->id,
                'image_path' => $path,
            ]);
        }
    }

    // Insert images for 'Full House'
    $winningPatternFullHouse = WinningPattern::where('name', 'Full House')->first();
    if ($winningPatternFullHouse) {
        $imagePathsFullHouse = [
            'images/full-house/1.png',
        ];

        foreach ($imagePathsFullHouse as $path) {
            DB::table('pattern_images')->insert([
                'winning_pattern_id' => $winningPatternFullHouse->id,
                'image_path' => $path,
            ]);
        }
    }
}

}
