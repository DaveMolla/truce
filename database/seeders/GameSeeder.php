<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $branchUsers = Branch::all()->pluck("id");

        foreach ($branchUsers as $branchUser) {
            Game::create([
                'branch_user_id' => $branchUser,
                'bet_amount' => rand(1, 100),
                'total_players' => rand(1, 50),
                'total_calls' => rand(1, 100),
                'status' => 'pending',
                'total_bet_amount' => rand(100, 5000),
                'profit' => rand(-100, 1000),
            ]);
        }

    }
}
