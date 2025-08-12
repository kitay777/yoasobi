<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 固定のテストユーザー
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'lat' => 35.681236,  // 東京駅
            'lng' => 139.767125,
            'share_location' => true,
            'location_updated_at' => now(),
        ]);

        // 東京駅近辺に30人
        User::factory()->count(30)->nearbyTokyo(5)->create();

        // 大阪駅近辺に10人
        User::factory()->count(10)->nearby(34.702485, 135.495951, 4)->create();

        // 位置未設定ユーザー5人
        User::factory()->count(5)->create();
    }
}
