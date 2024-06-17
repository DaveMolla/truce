<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Branch;
use App\Models\SuperAgent;
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
        // $admin = User::create([
        //     'name' => 'Admin User',
        //     'phone' => '0918810849',
        //     'role' => 'admin',
        //     'address' => 'Demoz-Admin',
        //     'password' => Hash::make('password'),
        // ]);

        // $agentUser = User::create([
        //     'name' => 'Agent User',
        //     'phone' => '1111111111',
        //     'role' => 'agent',
        //     'address' => 'Agent Address',
        //     'password' => Hash::make('password'),
        // ]);

        // $branchUser = User::create([
        //     'name' => 'Branch User',
        //     'phone' => '2222222222',
        //     'role' => 'branch',
        //     'address' => 'Branch Address',
        //     'password' => Hash::make('password'),
        //     'cut_off_percent' => '20'
        // ]);
        $superAgentUser = User::create([
            'name' => 'Super Agent 2',
            'phone' => '3333333334',
            'role' => 'super_agent',
            'address' => 'Super Agent Address',
            'password' => Hash::make('password'),
            // 'cut_off_percent' => '20'
        ]);

        // Create agent
        // $agent = Agent::create([
        //     'user_id' => $agentUser->id,
        // ]);

        $superAgent = SuperAgent::create([
            'user_id' => $superAgentUser->id,
        ]);

        // // Create branch and assign to agent
        // $branch = Branch::create([
        //     'user_id' => $branchUser->id,
        //     'agent_id' => $agent->id,
        // ]);
        // $branch = Branch::create([
        //     'user_id' => $branchUser->id,
        // ]);
    }
}
