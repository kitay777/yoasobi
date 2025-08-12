<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // 共有フラグ/更新時刻：無ければ追加
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'share_location')) {
                $table->boolean('share_location')->default(true)->index();
            }
            if (!Schema::hasColumn('users', 'location_updated_at')) {
                $table->timestamp('location_updated_at')->nullable()->index();
            }
        });

        // ① location 列を一旦 NULL で追加（SRID 4326 試行 → ダメなら素の POINT）
        if (!Schema::hasColumn('users', 'location')) {
            try {
                DB::statement('ALTER TABLE `users` ADD COLUMN `location` POINT SRID 4326 NULL');
            } catch (\Throwable $e) {
                DB::statement('ALTER TABLE `users` ADD COLUMN `location` POINT NULL');
            }
        }

        // ② 既存行を一時値で埋める（デフォルト不可のため）
        //    SRID 4326 を付けられる環境なら付与しておく
        try {
            DB::statement('UPDATE `users` SET `location` = ST_SRID(POINT(0,0), 4326) WHERE `location` IS NULL');
        } catch (\Throwable $e) {
            DB::statement('UPDATE `users` SET `location` = POINT(0,0) WHERE `location` IS NULL');
        }

        // ③ NOT NULL に変更（SRID 4326 指定できるなら付ける）
        try {
            DB::statement('ALTER TABLE `users` MODIFY COLUMN `location` POINT NOT NULL SRID 4326');
        } catch (\Throwable $e) {
            DB::statement('ALTER TABLE `users` MODIFY COLUMN `location` POINT NOT NULL');
        }

        // ④ SPATIAL INDEX を作成（無ければ）
        $idxExists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', 'users')
            ->where('INDEX_NAME', 'users_location_spatial_index')
            ->exists();

        if (!$idxExists) {
            DB::statement('CREATE SPATIAL INDEX `users_location_spatial_index` ON `users` (`location`)');
        }
    }

    public function down(): void {
        // インデックスがあれば削除
        $idxExists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', 'users')
            ->where('INDEX_NAME', 'users_location_spatial_index')
            ->exists();

        if ($idxExists) {
            DB::statement('ALTER TABLE `users` DROP INDEX `users_location_spatial_index`');
        }

        // 列があれば削除
        if (Schema::hasColumn('users', 'location')) {
            DB::statement('ALTER TABLE `users` DROP COLUMN `location`');
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'share_location')) {
                $table->dropColumn('share_location');
            }
            if (Schema::hasColumn('users', 'location_updated_at')) {
                $table->dropColumn('location_updated_at');
            }
        });
    }
};
