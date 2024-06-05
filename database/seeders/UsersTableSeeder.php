<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create users
        $admin = User::create([
            'name' => 'Admin User',
            'phone' => '1234567890',
            'role' => 'admin',
            'address' => 'Admin Address',
            'password' => Hash::make('password'),
        ]);

        $agentUser = User::create([
            'name' => 'Agent User',
            'phone' => '01111111111',
            'role' => 'agent',
            'address' => 'Agent Address',
            'password' => Hash::make('password'),
        ]);

        $branchUser = User::create([
            'name' => 'Branch User',
            'phone' => '02222222222',
            'role' => 'branch',
            'address' => 'Branch Address',
            'password' => Hash::make('password'),
            'cutt_off_percent' => '20'
        ]);

        // Create agent
        $agent = Agent::create([
            'user_id' => $agentUser->id,
        ]);

        // // Create branch and assign to agent
        // $branch = Branch::create([
        //     'user_id' => $branchUser->id,
        //     'agent_id' => $agent->id,
        // ]);
        $branch = Branch::create([
            'user_id' => $branchUser->id,
        ]);
    }
}
