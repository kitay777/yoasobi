<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,

            // ここから位置情報系（migration 済み前提）
            'lat' => null,
            'lng' => null,
            'share_location' => true,
            'location_updated_at' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }

    /**
     * 任意の座標を直指定
     */
    public function located(float $lat, float $lng): static
    {
        return $this->state(fn () => [
            'lat' => $lat,
            'lng' => $lng,
            'location_updated_at' => now(),
        ]);
    }

    /**
     * 指定中心点の半径km以内にランダム配置
     * @param float $centerLat
     * @param float $centerLng
     * @param float $radiusKm  デフォルト 3km
     */
    public function nearby(float $centerLat, float $centerLng, float $radiusKm = 3.0): static
    {
        return $this->state(function () use ($centerLat, $centerLng, $radiusKm) {
            // 半径kmの円内に一様分布させる
            $r = $radiusKm * 1000; // m
            $earth = 6371000;      // m
            $rand = fn() => mt_rand() / mt_getrandmax();
            $u = $rand(); $v = $rand();
            $dist = $r * sqrt($u);         // 円内一様
            $bearing = 2 * M_PI * $v;      // 0..2π

            $angDist = $dist / $earth;
            $lat1 = deg2rad($centerLat);
            $lng1 = deg2rad($centerLng);

            $lat2 = asin(
                sin($lat1) * cos($angDist) +
                cos($lat1) * sin($angDist) * cos($bearing)
            );
            $lng2 = $lng1 + atan2(
                sin($bearing) * sin($angDist) * cos($lat1),
                cos($angDist) - sin($lat1) * sin($lat2)
            );

            return [
                'lat' => rad2deg($lat2),
                'lng' => rad2deg($lng2),
                'share_location' => true,
                'location_updated_at' => now(),
            ];
        });
    }

    /**
     * 東京駅近辺にランダム配置（見た目確認用）
     */
    public function nearbyTokyo(float $radiusKm = 3.0): static
    {
        return $this->nearby(35.681236, 139.767125, $radiusKm);
    }
}
