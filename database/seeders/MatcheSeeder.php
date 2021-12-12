<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatcheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // team id 1 (start)
        DB::table('matches')->insert([
            'rank' => 1,
            'home_team_id' => 1,
            'away_team_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 2,
            'home_team_id' => 1,
            'away_team_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 3,
            'home_team_id' => 1,
            'away_team_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // team id 1 (end)

        // team id 2 (start)
        DB::table('matches')->insert([
            'rank' => 4,
            'home_team_id' => 2,
            'away_team_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 5,
            'home_team_id' => 2,
            'away_team_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 6,
            'home_team_id' => 2,
            'away_team_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // team id 2 (end)

        // team id 3 (start)
        DB::table('matches')->insert([
            'rank' => 7,
            'home_team_id' => 3,
            'away_team_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 8,
            'home_team_id' => 3,
            'away_team_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 9,
            'home_team_id' => 3,
            'away_team_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // team id 3 (end)

        // team id 4 (start)
        DB::table('matches')->insert([
            'rank' => 8,
            'home_team_id' => 4,
            'away_team_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 9,
            'home_team_id' => 4,
            'away_team_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('matches')->insert([
            'rank' => 10,
            'home_team_id' => 4,
            'away_team_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // team id 4 (end)
    }
}
